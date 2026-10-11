/** Public-only acceptance checks: no login, credentials, orders or messages. */
const { chromium } = require('playwright');
const fs = require('node:fs');
const assert = require('node:assert/strict');
const home = 'https://maqwiorganics.gt.tc/';
const output = 'frontend-qa';
fs.mkdirSync(output, { recursive: true });
(async () => {
  const executablePath = ['/usr/bin/google-chrome', '/usr/bin/chromium', '/usr/bin/chromium-browser'].find(fs.existsSync);
  assert(executablePath, 'A normal Chromium browser must be installed on the runner');
  const browser = await chromium.launch({ executablePath });
  const context = await browser.newContext({ viewport: { width: 1440, height: 1000 } });
  const report = { widths: [], errors: [], checked: [] };
  try {
    for (const width of [320, 390, 768, 1440]) {
      const page = await context.newPage();
      page.on('pageerror', e => report.errors.push(e.message));
      await page.setViewportSize({ width, height: 900 });
      await page.goto(home + '?frontendqa=' + width, { waitUntil: 'domcontentloaded', timeout: 90000 });
      // Let the host's normal browser challenge and WordPress redirects run naturally.
      await page.locator('.mq-collection-details').waitFor({ timeout: 90000 });
      await page.evaluate(() => document.fonts.ready);
      assert.equal(await page.locator('main h1').count(), 1, 'One H1 at ' + width);
      assert.equal(await page.locator('.mq-collection-detail').count(), 4, 'Four collection panels');
      assert.equal(await page.locator('.mq-cart,.mq-account,.mq-mobile-account').count(), 0, 'No ordering/account links');
      assert(await page.locator('link[href*="style.css?ver=0.3.0"]').count(), 'Current stylesheet version');
      assert(await page.locator('meta[name="description"]').getAttribute('content'), 'Homepage description');
      for (const image of await page.locator('main img').all()) {
        if (await image.isVisible()) {
          await image.scrollIntoViewIfNeeded();
          await image.evaluate(img => img.decode());
        }
      }
      await page.evaluate(() => window.scrollTo(0, 0));
      const metrics = await page.evaluate(() => ({
        width: innerWidth,
        scrollWidth: document.documentElement.scrollWidth,
        fontLoaded: document.fonts.check('16px "Maqwi Garamond"'),
        brokenVisibleImages: [...document.querySelectorAll('main img')].filter(img => img.getBoundingClientRect().height > 0 && (!img.complete || !img.naturalWidth)).length
      }));
      assert(metrics.scrollWidth <= width + 1, 'Horizontal overflow at ' + width + ': ' + metrics.scrollWidth);
      assert(metrics.fontLoaded, 'Brand font loaded');
      assert.equal(metrics.brokenVisibleImages, 0, 'Images loaded');
      await page.screenshot({ path: output + '/home-' + width + '.png', fullPage: true });
      if (width < 640) {
        const menu = page.locator('.mq-mobile-nav');
        const summary = menu.locator('summary');
        await summary.click();
        assert(await menu.evaluate(el => el.open), 'Mobile menu opens');
        await page.keyboard.press('Escape');
        assert(!(await menu.evaluate(el => el.open)), 'Escape closes mobile menu');
        assert(await summary.evaluate(el => el === document.activeElement), 'Focus returns to menu trigger');
        await summary.click();
        await menu.getByRole('link', { name: 'Questions', exact: true }).click();
        assert(!(await menu.evaluate(el => el.open)), 'Navigation closes mobile menu');
        assert(page.url().endsWith('#questions'), 'Mobile Questions anchor');
      }
      await page.locator('.mq-category h3 a').first().click();
      await page.waitForFunction(() => location.hash === '#collection-honey' && document.getElementById('collection-honey').open);
      const honey = page.locator('#collection-honey');
      assert(await honey.locator('.mq-detail-note').isVisible(), 'Concept status visible in collection');
      await honey.locator('img').evaluate(img => img.decode());
      const panelMetrics = await honey.evaluate(el => ({ x: el.getBoundingClientRect().x, right: el.getBoundingClientRect().right, scrollWidth: document.documentElement.scrollWidth }));
      assert(panelMetrics.x >= -1 && panelMetrics.right <= width + 1 && panelMetrics.scrollWidth <= width + 1, 'Expanded panel fits viewport');
      await page.screenshot({ path: output + '/collection-' + width + '.png' });
      const faq = page.locator('.mq-faq details').first();
      await faq.locator('summary').click();
      assert(await faq.evaluate(el => el.open), 'FAQ opens');
      assert((await faq.innerText()).includes('Orders are not open yet'), 'Accurate order status');
      for (const link of await page.locator('a[href*="#"]').all()) {
        const href = await link.getAttribute('href');
        const url = new URL(href, home);
        if (url.hostname !== new URL(home).hostname || !url.hash) continue;
        assert(await page.evaluate(id => !!document.getElementById(id), url.hash.slice(1)), 'Anchor target exists: ' + href);
      }
      report.widths.push({ ...metrics, collectionFits: true, menu: width < 640 ? 'Escape/focus/navigation passed' : 'desktop', faq: 'passed' });
      await page.close();
    }
    const page = await context.newPage();
    page.on('pageerror', e => report.errors.push(e.message));
    await page.goto(home + '?frontendqa=keyboard', { waitUntil: 'domcontentloaded' });
    await page.locator('.mq-collection-details').waitFor();
    await page.keyboard.press('Tab');
    assert(await page.locator('.mq-skip-link').evaluate(el => el === document.activeElement), 'First Tab reaches skip link');
    await page.keyboard.press('Enter');
    assert(page.url().endsWith('#main'), 'Skip link reaches main content');
    for (const route of ['checkout/', 'cart/', 'my-account/', 'shop/']) {
      await page.goto(home + route, { waitUntil: 'domcontentloaded' });
      await page.locator('.mq-hero').waitFor();
      assert.equal(new URL(page.url()).pathname, '/', 'Closed commerce route: ' + route);
      assert.equal(await page.locator('form.checkout').count(), 0, 'No checkout exposed');
    }
    for (const slug of ['honey', 'desi-ghee', 'achaar', 'itar']) {
      await page.goto(home + 'product-category/' + slug + '/', { waitUntil: 'domcontentloaded' });
      await page.waitForFunction(id => document.getElementById(id)?.open, 'collection-' + slug);
      assert(page.url().endsWith('#collection-' + slug), 'Legacy collection route: ' + slug);
    }
    const response = await page.goto(home + 'maqwi-public-qa-missing-page/', { waitUntil: 'domcontentloaded' });
    await page.locator('.mq-404').waitFor();
    assert.equal(response.status(), 404, 'Missing page returns real 404');
    assert.equal(await page.locator('main h1').count(), 1, '404 has one H1');
    await page.screenshot({ path: output + '/404.png', fullPage: true });
    await page.getByRole('link', { name: 'Back to Maqwi', exact: true }).click();
    await page.locator('.mq-hero').waitFor();
    assert.equal(report.errors.length, 0, 'No browser runtime errors');
    report.checked = ['Images', 'H1', 'font', 'metadata', 'all anchor targets', 'collection deep links', 'FAQ', 'mobile menu Escape and focus', 'skip link', 'closed commerce routes', 'true branded 404'];
    fs.writeFileSync(output + '/report.json', JSON.stringify(report, null, 2));
    console.log(JSON.stringify(report));
  } catch (error) {
    fs.writeFileSync(output + '/report.json', JSON.stringify({ ...report, failure: error.message }, null, 2));
    for (const page of context.pages()) await page.screenshot({ path: output + '/failure.png', fullPage: true }).catch(() => {});
    throw error;
  } finally {
    await browser.close();
  }
})().catch(error => { console.error(error); process.exitCode = 1; });
