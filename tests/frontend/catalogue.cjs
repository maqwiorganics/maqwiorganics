/** Read-only public acceptance; no orders, stock changes, accounts or messages. */
const {chromium}=require('playwright');
const fs=require('node:fs');
const assert=require('node:assert/strict');
const base='https://maqwiorganics.gt.tc/',out='frontend-qa';
fs.mkdirSync(out,{recursive:true});
(async()=>{
 const browser=await chromium.launch({executablePath:['/usr/bin/google-chrome','/usr/bin/chromium'].find(fs.existsSync)});
 const context=await browser.newContext();
 const report={checks:[],runtimeErrors:[],expectedBlockedRequests:0};
 try{
  for(const width of [320,390,768,1440]){
   const page=await context.newPage();await page.setViewportSize({width,height:900});
   page.on('pageerror',e=>report.runtimeErrors.push(e.message));
   async function visit(route){console.log('Visiting',width,route);await page.goto(base+route,{waitUntil:'domcontentloaded',timeout:90000});await page.locator('.mq-header').waitFor({timeout:90000});await page.waitForLoadState('load');}
   async function check(name){
    assert.equal(await page.locator('main h1').count(),1,name+' H1');
    const copy=await page.locator('body').innerText();
    assert(!/\bAI\b|AI.generated|packaging concept|sample catalogue|storefront preview|for this preview|proposed guidance|for review|prototype|thoughtful beginning|daily fragrance ritual/i.test(copy),name+' professional customer copy');
    for(const img of await page.locator('main img').all()){if(await img.isVisible()){await img.scrollIntoViewIfNeeded({timeout:10000});await Promise.race([img.evaluate(i=>i.decode()),new Promise((_,reject)=>setTimeout(()=>reject(new Error('Image decode timed out: '+name)),15000))]);}}
    const m=await page.evaluate(()=>({width:innerWidth,scroll:document.documentElement.scrollWidth,broken:[...document.querySelectorAll('main img')].filter(i=>i.getBoundingClientRect().height>0&&(!i.complete||!i.naturalWidth)).length}));
    assert(m.scroll<=width+1,name+' horizontal overflow');assert.equal(m.broken,0,name+' broken images');
    report.checks.push({width,name,...m});
    await page.evaluate(()=>scrollTo(0,0));await page.waitForFunction(()=>scrollY===0);
    await page.screenshot({path:out+'/'+name+'-'+width+'.png',fullPage:true});
    console.log(JSON.stringify({passed:name,width}));
   }
   await visit('?catalogue-release=20261016');
   console.log('Catalogue health:', await page.locator('meta[name="maqwi-catalogue-status"]').getAttribute('content'));
   console.log('Photo integrity:', await page.locator('meta[name="maqwi-photo-integrity"]').count() ? await page.locator('meta[name="maqwi-photo-integrity"]').getAttribute('content') : 'no mismatch');
   await page.screenshot({path:out+'/release-status-'+width+'.png',fullPage:true});
   assert.equal(await page.locator('.mq-product-card').count(),4,'Four catalogue cards');
   const themeVersion=fs.readFileSync('wp-content/themes/maqwi-organics/style.css','utf8').match(/^Version: (.+)$/m)[1];
   assert(await page.locator('link[href*="style.css?ver='+themeVersion+'"]').count(),'Current theme');
   assert.equal(await page.locator('.mq-announcement').innerText(),'Meet the Maqwi collection · orders open soon');
   assert.equal(await page.locator('.mq-premium-hero').count(),1,'Premium hero renders once');
   assert.equal(await page.locator('.mq-premium-primary').getAttribute('href'),'#the-collection');
   assert.equal(await page.locator('.mq-premium-story').getAttribute('href'),base+'about/');
   const heroImg=page.locator('.mq-hero-slide').first().locator('img');
   assert((await heroImg.getAttribute('src')).includes('hero-collection-v3.webp'));
   assert.equal(await heroImg.getAttribute('fetchpriority'),'high');
   await page.evaluate(()=>document.fonts.ready);
   await page.locator('.mq-premium-copy').evaluate(e=>Promise.all(e.getAnimations().map(a=>a.finished.catch(()=>{}))));
   if(width<1100){const c=await page.locator('.mq-premium-copy').boundingBox(),m=await page.locator('.mq-premium-media').boundingBox();assert(c.y+c.height<=m.y+10,'Mobile copy above image');}
   await page.evaluate(()=>document.fonts.ready);
   for(const selector of ['.mq-premium-copy','.mq-premium-media']){await page.locator(selector).evaluate(e=>Promise.all(e.getAnimations().map(a=>a.finished.catch(()=>{}))));}
   await check('home');
   if(width<=900){
    const menu=page.locator('.mq-mobile-nav');await menu.locator('summary').click();
    assert(await menu.evaluate(e=>e.open),'Menu opens');
    const bag=await page.locator('.mq-cart').boundingBox(),trigger=await menu.locator('summary').boundingBox();
    assert(bag.x+bag.width+8<=trigger.x,'Bag and menu controls have clear separation');
    const nav=await menu.locator('nav').boundingBox(), hero=await page.locator('.mq-premium-hero').boundingBox();
    assert(nav.x>=0&&nav.x+nav.width<=width+1,'Menu fits viewport');
    assert(nav.y+nav.height<=hero.y+1,'Expanded menu does not cover hero');
    await page.screenshot({path:out+'/menu-'+width+'.png',fullPage:false});
    await page.keyboard.press('Escape');assert(!(await menu.evaluate(e=>e.open)),'Escape closes menu');
   }
   await page.screenshot({path:out+'/hero-'+width+'.png',fullPage:false});
   await page.locator('.mq-premium-primary').click();await page.waitForFunction(()=>location.hash==='#the-collection');
   await page.emulateMedia({reducedMotion:'reduce'});
   assert.equal(await page.locator('.mq-premium-copy').evaluate(e=>getComputedStyle(e).animationName),'none','Reduced motion respected');
   await page.emulateMedia({reducedMotion:'no-preference'});
   await visit('shop/');assert.equal(await page.locator('ul.products>li.product').count(),4);await check('shop');
   const products=[['honey','4,500','1 kg'],['desi-ghee','3,500','1 kg'],['itar','200','12 ml'],['achaar',null,null]];
   for(const [slug,price,size] of products){
    await visit('product/maqwi-'+slug+'/');
    const summary=await page.locator('.summary').innerText();
    if(price){assert(summary.includes(price),'Confirmed price '+slug);assert((await page.locator('main').innerText()).includes(size),'Pack size '+slug);}
    else {assert((await page.locator('main').innerText()).includes('Price to be confirmed'),'Achaar has no fabricated price');}
    assert.equal(await page.locator('.woocommerce-product-gallery__wrapper>.woocommerce-product-gallery__image').count(),4,'Four native gallery images '+slug);
    assert.equal(await page.locator('.mq-concept-gallery').count(),0,'Old concept fallback retired');
    const thumbs=page.locator('.flex-control-thumbs img');
    assert.equal(await thumbs.count(),4,'Four usable gallery thumbnails '+slug);
    const firstThumb=await thumbs.first().boundingBox(),lastThumb=await thumbs.last().boundingBox();
    assert(Math.abs(firstThumb.y-lastThumb.y)<3,'Gallery thumbnails share one row '+slug);
    for(let n=0;n<4;n++){await thumbs.nth(n).click();await page.locator('.woocommerce-product-gallery__wrapper>.woocommerce-product-gallery__image').nth(n).locator('img').first().evaluate(i=>i.decode());}
    await thumbs.first().click();
    assert(!summary.includes('Sample product'),'No sample product label');
    await page.waitForFunction(()=>[...document.querySelectorAll('.single_add_to_cart_button')].every(b=>b.disabled||b.classList.contains('disabled')),{},{timeout:10000});
    for(const button of await page.getByRole('button',{name:'Add to cart',exact:true}).filter({visible:true}).all()){const classes=await button.getAttribute('class');console.log('Purchase control',slug,classes);assert((await button.isDisabled())||/(?:^|\s)disabled(?:\s|$)/.test(classes),'Unknown stock cannot be ordered');}
    const variationData=await page.locator('form.variations_form').count()?await page.locator('form.variations_form').getAttribute('data-product_variations'):null;
    if(variationData&&variationData!=='false'){const variations=JSON.parse(variationData);console.log('Native variation availability',slug,JSON.stringify(variations.map(v=>({id:v.variation_id,inStock:v.is_in_stock,purchasable:v.is_purchasable}))));assert(variations.every(v=>v.is_in_stock===false&&v.is_purchasable===false),'Every offer remains unavailable');}
    if(slug==='honey'){assert(summary.includes('500 g')&&summary.includes('1 kg'),'Honey pack distinction retained');}
    if(slug==='desi-ghee'){assert(summary.includes('500 ml')&&summary.includes('1 kg'),'Ghee pack distinction retained');}
    await check(slug);
   }
   for(const route of ['about','contact','faq','delivery','returns','privacy-preview','cart','my-account','product-category/honey','product-category/desi-ghee','product-category/achaar','product-category/itar']){await visit(route+'/');await check(route);}
   for(const method of ['GET','POST']){
    const response=await page.request.fetch(base+'?rest_route=/wc/store/v1/checkout',{method});
    assert.equal(response.status(),403,'Closed checkout '+method);
    const data=await response.json();assert.equal(data.code,'maqwi_orders_closed');report.expectedBlockedRequests++;
   }
   await page.close();
  }
  assert.equal(report.runtimeErrors.length,0,'No frontend runtime errors');
  report.passed=true;
 } finally {fs.writeFileSync(out+'/catalogue-report.json',JSON.stringify(report,null,2));await browser.close();}
})().catch(e=>{console.error(e);process.exitCode=1;});
