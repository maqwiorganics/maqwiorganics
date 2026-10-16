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
   async function visit(route){await page.goto(base+route,{waitUntil:'domcontentloaded',timeout:90000});await page.locator('.mq-header').waitFor({timeout:90000});await page.waitForLoadState('load');}
   async function check(name){
    assert.equal(await page.locator('main h1').count(),1,name+' H1');
    for(const img of await page.locator('main img').all()){if(await img.isVisible()){await img.evaluate(i=>i.decode());}}
    const m=await page.evaluate(()=>({width:innerWidth,scroll:document.documentElement.scrollWidth,broken:[...document.querySelectorAll('main img')].filter(i=>i.getBoundingClientRect().height>0&&(!i.complete||!i.naturalWidth)).length}));
    assert(m.scroll<=width+1,name+' horizontal overflow');assert.equal(m.broken,0,name+' broken images');
    report.checks.push({width,name,...m});
    await page.screenshot({path:out+'/'+name+'-'+width+'.png',fullPage:true});
    console.log(JSON.stringify({passed:name,width}));
   }
   await visit('?catalogue-release=20261016');
   assert.equal(await page.locator('.mq-product-card').count(),4,'Four catalogue cards');
   assert(await page.locator('link[href*="style.css?ver=0.5.0"]').count(),'Current theme');
   assert.equal(await page.locator('.mq-announcement').innerText(),'Meet the Maqwi collection · orders open soon');
   await check('home');
   await visit('shop/');assert.equal(await page.locator('ul.products>li.product').count(),4);await check('shop');
   const products=[['honey','4,500','1 kg'],['desi-ghee','3,500','1 kg'],['itar','200','12 ml'],['achaar',null,null]];
   for(const [slug,price,size] of products){
    await visit('product/maqwi-'+slug+'/');
    const summary=await page.locator('.summary').innerText();
    if(price){assert(summary.includes(price),'Confirmed price '+slug);assert((await page.locator('main').innerText()).includes(size),'Pack size '+slug);}
    else {assert((await page.locator('main').innerText()).includes('Price to be confirmed'),'Achaar has no fabricated price');}
    assert.equal(await page.locator('.woocommerce-product-gallery__wrapper>.woocommerce-product-gallery__image').count(),4,'Four native gallery images '+slug);
    assert.equal(await page.locator('.mq-concept-gallery').count(),0,'Old concept fallback retired');
    assert(!summary.includes('Sample product'),'No sample product label');
    assert.equal(await page.getByRole('button',{name:'Add to cart',exact:true}).filter({visible:true}).count(),0,'Unknown stock cannot be ordered');
    await check(slug);
   }
   for(const route of ['about','contact','faq','delivery','returns','privacy-preview','cart']){await visit(route+'/');await check(route);}
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
