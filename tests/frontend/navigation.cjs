/** Public navigation review: no orders, accounts or messages are created. */
const {chromium}=require('playwright');
const fs=require('node:fs'),assert=require('node:assert/strict');
const base='https://maqwiorganics.gt.tc/',out='frontend-qa';
fs.mkdirSync(out,{recursive:true});
(async()=>{
 const browser=await chromium.launch({executablePath:['/usr/bin/google-chrome','/usr/bin/chromium'].find(fs.existsSync)});
 const context=await browser.newContext();
 const report={checks:[],runtimeErrors:[]};
 try{
  for(const width of [320,390,768,1024,1440]){
   const page=await context.newPage();await page.setViewportSize({width,height:900});
   page.on('pageerror',e=>report.runtimeErrors.push(e.message));
   for(const route of ['?navigation=081','shop/','product/maqwi-honey/']){
    console.log('Reviewing navigation',width,route);
    await page.goto(base+route,{waitUntil:'domcontentloaded',timeout:60000});
    await page.locator('.mq-header').waitFor({timeout:30000});
    await page.waitForLoadState('load');await page.evaluate(()=>document.fonts.ready);
    await page.locator('link[href*="style.css?ver=0.8.1"]').waitFor({state:'attached'});
    const cart=page.getByRole('link',{name:'Shopping bag',exact:true});
    assert.equal(await cart.count(),1);assert.equal(await cart.locator('svg').count(),1);
    assert.equal((await cart.innerText()).trim(),'0','Cart uses an icon and live count');
    assert.equal(await cart.getAttribute('href'),base+'cart/');
    const metrics=await page.evaluate(()=>({width:innerWidth,scroll:document.documentElement.scrollWidth}));
    assert(metrics.scroll<=width+1,'No horizontal overflow');
    if(width<=900){
     const menu=page.locator('.mq-mobile-nav'),summary=menu.locator('summary');
     assert(await summary.isVisible());
     assert.equal((await summary.innerText()).trim(),'','Icon-only menu');
     assert.equal(await summary.getAttribute('aria-label'),'Navigation menu');
     assert.equal(await summary.locator('.mq-menu-glyph>span').count(),3,'Three parallel bars');
     const style=await summary.evaluate(e=>{const s=getComputedStyle(e);return {background:s.backgroundColor,border:s.borderTopWidth,color:s.color}});
     assert.equal(style.background,'rgba(0, 0, 0, 0)');assert.equal(style.border,'0px');assert.equal(style.color,'rgb(47, 93, 58)');
     const bars=await summary.locator('.mq-menu-glyph>span').evaluateAll(es=>es.map(e=>e.getBoundingClientRect().toJSON()));
     assert(bars.every(b=>b.width===22),'Equal-width bars');assert(bars[1].y>bars[0].y&&bars[2].y>bars[1].y,'Three distinct parallel bars');
     assert.equal(await summary.evaluate(e=>getComputedStyle(e,'::after').content),'none','No generic plus/minus');
     const bagRect=await cart.boundingBox(),triggerRect=await summary.boundingBox();
     assert(bagRect.x+bagRect.width+8<=triggerRect.x,'Separated touch controls');
     assert(bagRect.width>=42&&bagRect.height>=44&&triggerRect.height>=44,'Comfortable touch targets');
     await summary.focus();await page.keyboard.press('Enter');
     assert(await menu.evaluate(e=>e.open),'Keyboard opens menu');
     assert.equal((await summary.innerText()).trim(),'');
     const navRect=await menu.locator('nav').boundingBox(),main=await page.locator('main').boundingBox();
     assert(navRect.x>=0&&navRect.x+navRect.width<=width+1,'Expanded navigation fits viewport');
     assert(navRect.y+navRect.height<=main.y+1,'Navigation stays above page content');
     await summary.locator('.mq-menu-glyph>span').evaluateAll(es=>Promise.all(es.flatMap(e=>e.getAnimations().map(a=>a.finished.catch(()=>{})))));
     if(route.startsWith('?'))await page.screenshot({path:out+'/navigation-open-'+width+'.png',fullPage:false});
     await page.keyboard.press('Escape');assert(!(await menu.evaluate(e=>e.open)));assert(await summary.evaluate(e=>e===document.activeElement));
     await summary.click();await page.locator('main h1').click();assert(!(await menu.evaluate(e=>e.open)),'Outside click closes menu');
     if(route.startsWith('?')){
      await summary.click();await menu.getByRole('link',{name:'Shop',exact:true}).click();
      await page.waitForURL(base+'shop/');assert(!(await page.locator('.mq-mobile-nav').evaluate(e=>e.open)),'Navigation selection closes menu');
      await page.goBack({waitUntil:'load'});
      await page.locator('.mq-mobile-nav summary').click();
      await page.setViewportSize({width:1100,height:900});await page.waitForFunction(()=>!document.querySelector('.mq-mobile-nav').open);
      await page.setViewportSize({width,height:900});
     }
    }else{
     assert(await page.locator('.mq-desktop-nav').isVisible());
     assert(await page.getByRole('link',{name:'My account',exact:true}).filter({visible:true}).count()>=1);
     assert(!(await page.locator('.mq-mobile-nav').isVisible()));
    }
    await page.locator('main h1').focus().catch(()=>{});
    await page.locator('.mq-premium-copy,.mq-premium-media').evaluateAll(es=>Promise.all(es.flatMap(e=>e.getAnimations().map(a=>a.finished.catch(()=>{})))));
    await page.screenshot({path:out+'/navigation-'+(route.startsWith('?')?'home':route.startsWith('shop')?'shop':'product')+'-'+width+'.png',fullPage:false});
    report.checks.push({width,route,...metrics});
   }
   // Follow the public bag link without submitting any form.
   await page.getByRole('link',{name:'Shopping bag',exact:true}).click();await page.waitForURL(base+'cart/');
   assert(await page.locator('main h1').isVisible(),'Bag destination loads');
   await page.close();
  }
  assert.equal(report.runtimeErrors.length,0);report.passed=true;
 }finally{fs.writeFileSync(out+'/navigation-report.json',JSON.stringify(report,null,2));await browser.close();}
})().catch(e=>{console.error(e);process.exitCode=1;});
