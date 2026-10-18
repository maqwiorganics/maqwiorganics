/** Verify automatic image/copy coordination; no orders or form submissions. */
const {chromium}=require('playwright');const fs=require('node:fs'),assert=require('node:assert/strict');
const base=process.env.HERO_BASE_URL||'https://maqwiorganics.gt.tc/',out='frontend-qa';fs.mkdirSync(out,{recursive:true});
(async()=>{
 const browser=await chromium.launch({executablePath:['/usr/bin/google-chrome','/usr/bin/chromium'].find(fs.existsSync)});
 const context=await browser.newContext();const report={checks:[],errors:[]};
 try{
  for(const width of [320,390,768,1024,1366,1440,1920]){
   const page=await context.newPage();await page.setViewportSize({width,height:width===1366?768:900});page.on('pageerror',e=>report.errors.push(e.message));
   await page.goto(base+'?hero=mobile-editorial-0110',{waitUntil:'load',timeout:90000});await page.locator('.mq-slideshow-hero').waitFor({timeout:30000});await page.evaluate(()=>document.fonts.ready);
   const hero=page.locator('.mq-slideshow-hero'),slides=hero.locator('.mq-hero-slide');
   assert.equal(await slides.count(),3);assert.equal(await hero.locator('button,.mq-hero-controls,.mq-hero-dots').count(),0,'No slider controls');
   for(let n=0;n<3;n++){
    await page.waitForFunction(n=>[...document.querySelectorAll('.mq-hero-slide')].findIndex(e=>!e.hidden)===n,n,{timeout:11000});
    const state=await hero.evaluate(h=>{const slide=h.querySelector('.mq-hero-slide:not([hidden])'),lines=[...h.querySelectorAll('.mq-hero-line')];return {expectedTitle:JSON.parse(slide.dataset.headline),title:lines.map(e=>e.textContent),expectedDescription:slide.dataset.description,description:h.querySelector('.mq-premium-description').textContent,expectedCTA:slide.dataset.cta,cta:h.querySelector('.mq-primary-label').textContent,expectedURL:slide.dataset.url,url:h.querySelector('.mq-premium-primary').getAttribute('href'),lineCounts:lines.map(e=>{const r=document.createRange();r.selectNodeContents(e);return r.getClientRects().length}),width:innerWidth,scroll:document.documentElement.scrollWidth};});
    assert.deepEqual(state.title,state.expectedTitle);assert.equal(state.description,state.expectedDescription);assert.equal(state.cta,state.expectedCTA);assert.equal(state.url,state.expectedURL);
    assert(state.lineCounts.every(n=>n===1),'Headline segments fit without overflow');assert(state.scroll<=width+1,'No horizontal overflow');
    await slides.nth(n).locator('img').evaluate(i=>i.decode());await slides.nth(n).evaluate(e=>Promise.all(e.getAnimations().map(a=>a.finished.catch(()=>{}))));await hero.locator('.mq-premium-copy').evaluate(e=>Promise.all(e.getAnimations().map(a=>a.finished.catch(()=>{}))));
    await page.screenshot({path:out+'/hero-slide-'+(n+1)+'-'+width+'.png',fullPage:false});report.checks.push({width,slide:n+1,title:state.title});
   }
   const dimensions=await hero.evaluate(h=>({height:h.getBoundingClientRect().height,collectionTop:document.querySelector('#the-collection').getBoundingClientRect().top}));
   if(width<768)assert(dimensions.height<=650,'Compact mobile hero');if(width>=1100)assert(dimensions.height<=660);if(width===1366)assert(dimensions.collectionTop<768);
   if(width===1366){
    await hero.locator('.mq-premium-primary').focus();const title=await hero.locator('h1').innerText();await page.waitForTimeout(7500);assert.equal(await hero.locator('h1').innerText(),title,'Focus pauses linked copy');
   }
   console.log('Automatic scene/copy checks passed',width);await page.close();
  }
  const reduce=await context.newPage();await reduce.emulateMedia({reducedMotion:'reduce'});await reduce.goto(base+'?hero=automatic-reduced-0100',{waitUntil:'load',timeout:90000});await reduce.locator('.mq-slideshow-hero').waitFor();
  await reduce.waitForTimeout(7500);assert.equal(await reduce.locator('.mq-hero-slide:not([hidden])').getAttribute('aria-label'),'1 of 3: The collection');await reduce.close();
  const p=await context.newPage();let blocked=false;await p.route('**/wp-content/themes/maqwi-organics/assets/public.js*',r=>{blocked=true;return r.abort();});await p.goto(base+'?hero=automatic-fallback-0100',{waitUntil:'load',timeout:90000});assert(blocked);assert(await p.locator('.mq-hero-slide').first().isVisible());assert.equal(await p.locator('.mq-slideshow-hero button').count(),0);await p.close();
  assert.equal(report.errors.length,0);report.passed=true;
 }finally{fs.writeFileSync(out+'/hero-carousel-report.json',JSON.stringify(report,null,2));await browser.close();}
})().catch(e=>{console.error(e);process.exitCode=1;});
