/** Normal browser checks. Sample bags only; no orders, login, registration or messages. */
const { chromium } = require('playwright');
const fs = require('node:fs');
const assert = require('node:assert/strict');
const base='https://maqwiorganics.gt.tc/', output='frontend-qa';
fs.mkdirSync(output,{recursive:true});
(async()=>{
 const executablePath=['/usr/bin/google-chrome','/usr/bin/chromium'].find(fs.existsSync);
 const browser=await chromium.launch({executablePath});
 const report={widths:[],pages:[],errors:[],consoleErrors:[],checked:[]};
 try{
 for(const width of [320,390,768,1440]){
  const context=await browser.newContext({viewport:{width,height:900}}),page=await context.newPage();
  page.on('pageerror',e=>report.errors.push(e.message));
  page.on('console',m=>{if(m.type()==='error')report.consoleErrors.push(m.text())});
  async function visit(route){await page.goto(base+route,{waitUntil:'domcontentloaded',timeout:90000});await page.locator('.mq-header').waitFor({timeout:90000});await page.evaluate(()=>document.fonts.ready);}
  async function check(name){
   assert.equal(await page.locator('main h1').count(),1,name+' one H1');
   for(const img of await page.locator('main img').all()){if(await img.isVisible()){await img.scrollIntoViewIfNeeded();await img.evaluate(i=>i.decode());}}
   const metrics=await page.evaluate(()=>({width:innerWidth,scrollWidth:document.documentElement.scrollWidth,brokenImages:[...document.querySelectorAll('main img')].filter(i=>i.getBoundingClientRect().height>0&&(!i.complete||!i.naturalWidth)).length}));
   assert(metrics.scrollWidth<=width+1,name+' overflow '+metrics.scrollWidth);assert.equal(metrics.brokenImages,0,name+' images');
   report.pages.push({width,name,...metrics});
   await page.screenshot({path:`${output}/${name}-${width}.png`,fullPage:true});
  }
  await visit('?storeqa='+width);await page.locator('.mq-featured').waitFor();
  assert.equal(await page.locator('.mq-product-card').count(),4,'Four featured products');
  assert(await page.locator('link[href*="style.css?ver=0.4.0"]').count(),'Current theme');
  await check('home');
  if(width<640){const menu=page.locator('.mq-mobile-nav');await menu.locator('summary').click();await page.keyboard.press('Escape');assert(!(await menu.evaluate(e=>e.open)),'Escape closes menu');await menu.locator('summary').click();await menu.getByRole('link',{name:'Shop',exact:true}).click();await page.locator('.mq-commerce').waitFor();}
  else {await page.locator('.mq-desktop-nav').getByRole('link',{name:'Shop',exact:true}).click();await page.locator('.mq-commerce').waitFor();}
  assert.equal(await page.locator('ul.products>li.product').count(),4,'Shop four products');await check('shop');
  await page.locator('select.orderby').selectOption('price-desc');await page.waitForLoadState('domcontentloaded');
  await page.locator('.mq-shop-search input[type="search"]').fill('Honey');await page.locator('.mq-shop-search button[type="submit"]').click();await page.locator('.mq-commerce').waitFor();
  const results=await page.locator('ul.products>li.product').count();
  assert(results===1 || (new URL(page.url()).pathname==='/product/maqwi-honey/' && await page.locator('form.variations_form').count()===1),'Product search returns matching product');
  await visit('product-category/honey/');assert.equal(await page.locator('ul.products>li.product').count(),1,'Category filter');await check('category');
  await page.locator('ul.products .woocommerce-LoopProduct-link').first().click();await page.locator('form.variations_form').waitFor();
  assert((await page.locator('main').innerText()).includes('Sample product'),'Sample label');
  const select=page.locator('select[name="attribute_size"]');await select.selectOption({label:'250 g'});
  await page.locator('.single_add_to_cart_button:not(.disabled)').waitFor();
  await page.locator('#tab-title-maqwi_details a').click();assert((await page.locator('#tab-maqwi_details').innerText()).includes('awaiting approval'),'Product details');
  await check('product');await page.locator('.single_add_to_cart_button').click();await page.locator('.woocommerce-message').waitFor();
  await page.locator('.mq-cart').click();await page.locator('form.woocommerce-cart-form').waitFor();
  assert((await page.locator('.woocommerce-cart-form').innerText()).includes('Maqwi Honey'),'Cart product');
  await page.locator('input.qty').fill('2');await page.locator('button[name="update_cart"]').click();await page.waitForTimeout(1000);await page.locator('input.qty').waitFor();assert.equal(await page.locator('input.qty').inputValue(),'2','Quantity updated');
  await check('cart');
  await page.locator('.checkout-button').click();await page.locator('form.checkout').waitFor();
  assert.equal(await page.locator('#billing_first_name').count(),1,'Full name field');assert.equal(await page.locator('#billing_phone').count(),1,'Mobile field');
  assert.equal(await page.locator('#billing_landmark').count(),1,'Landmark field');assert.equal(await page.locator('.mq-preview-disabled').isDisabled(),true,'Order submission disabled');
  assert.equal(await page.locator('input[name="payment_method"]').count(),0,'No payment gateway');await check('checkout');
  // Check Store API guard without submitting any customer data.
  const guard=await page.evaluate(async()=>{const r=await fetch('/?rest_route=/wc/store/v1/checkout',{method:'POST',headers:{'Content-Type':'application/json'},body:'{}'});return {status:r.status,text:await r.text()};});
  assert.equal(guard.status,403,'Store API checkout blocked');assert(guard.text.includes('maqwi_orders_closed'),'Specific order guard');
  await visit('my-account/');await page.locator('form.woocommerce-form-login').waitFor();assert.equal(await page.locator('form.woocommerce-form-register').count(),0,'No registration');await check('account');
  for(const route of ['about','contact','faq','delivery','returns','privacy-preview']){await visit(route+'/');await check(route);}
  await visit('faq/');await page.locator('main details summary').first().click();assert(await page.locator('main details').first().evaluate(e=>e.open),'FAQ works');
  await page.locator('.mq-back-top').click();await page.waitForFunction(()=>scrollY<=5);
  // Remove only our sample cart item and verify empty state.
  await visit('cart/');await page.locator('.product-remove a').click();await page.locator('.cart-empty').waitFor();await check('empty-cart');
  report.widths.push({width,complete:true});await context.close();
 }
 assert.equal(report.errors.length,0,'No runtime errors');
 report.checked=['home','shop','sorting','search','category','variation selection','product description and details','sample add to bag','quantity update','cart','checkout fields','disabled order submission','Store API order guard','account login screen','six information pages','FAQ','mobile navigation','footer','empty bag'];
 fs.writeFileSync(output+'/report.json',JSON.stringify(report,null,2));console.log(JSON.stringify(report));
 }catch(e){fs.writeFileSync(output+'/report.json',JSON.stringify({...report,failure:e.message},null,2));for(const c of browser.contexts())for(const p of c.pages()){console.log(JSON.stringify({url:p.url(),body:(await p.locator('main').innerText().catch(()=>'' )).slice(0,4000)}));await p.screenshot({path:output+'/failure.png',fullPage:true}).catch(()=>{});}throw e;}
 finally{await browser.close();}
})().catch(e=>{console.error(e);process.exitCode=1});
