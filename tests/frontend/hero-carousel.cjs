/** Public hero interaction and layout checks. No forms, orders or account changes. */
const {chromium}=require('playwright');
const fs=require('node:fs'),assert=require('node:assert/strict');
const base='https://maqwiorganics.gt.tc/',out='frontend-qa';fs.mkdirSync(out,{recursive:true});
(async()=>{
 const browser=await chromium.launch({executablePath:['/usr/bin/google-chrome','/usr/bin/chromium'].find(fs.existsSync)});
 const context=await browser.newContext();const report={checks:[],errors:[]};
 try{
 for(const width of [320,390,768,1024,1366,1440,1920]){
  const page=await context.newPage();await page.setViewportSize({width,height:width===1366?768:900});
  page.on('pageerror',e=>report.errors.push(e.message));
  await page.goto(base+'?hero=carousel-090',{waitUntil:'load',timeout:90000});
  await page.locator('.mq-slideshow-hero').waitFor({timeout:30000});await page.evaluate(()=>document.fonts.ready);
  const gallery=page.locator('.mq-hero-gallery'),slides=gallery.locator('.mq-hero-slide'),controls=gallery.locator('.mq-hero-controls');
  assert.equal(await slides.count(),3);assert(await controls.isVisible());
  const hero=page.locator('.mq-slideshow-hero');
  assert.equal(await hero.locator('h1').innerText(),'Everyday essentials.\nRooted in\ntradition.');
  assert.equal(await hero.locator('.mq-premium-description').innerText(),'Honey, desi ghee, achaar and itar — familiar favourites for everyday living.');
  assert.equal(await hero.locator('.mq-premium-note').count(),0,'Repeated category line removed');
  const lineCounts=await hero.locator('.mq-hero-line').evaluateAll(es=>es.map(e=>{const r=document.createRange();r.selectNodeContents(e);return r.getClientRects().length}));
  assert(lineCounts.every(n=>n===1),'Exactly three balanced headline lines');
  const first=slides.first().locator('img');await first.evaluate(i=>i.decode());
  assert.equal(await first.getAttribute('fetchpriority'),'high');
  async function active(n){await page.waitForFunction(n=>[...document.querySelectorAll('.mq-hero-slide')].findIndex(s=>!s.hidden)===n,n);await slides.nth(n).locator('img').evaluate(i=>i.decode());assert.equal(await gallery.locator('[aria-current=true]').getAttribute('data-slide'),String(n));}
  // Stop automatic rotation before screenshots and exercise each slide normally.
  await gallery.getByRole('button',{name:'Show the collection',exact:true}).click();await active(0);
  for(let n=0;n<3;n++){
   await gallery.locator('[data-slide="'+n+'"]').click();await active(n);
   await slides.nth(n).evaluate(e=>Promise.all(e.getAnimations().map(a=>a.finished.catch(()=>{}))));
   await page.screenshot({path:out+'/hero-slide-'+(n+1)+'-'+width+'.png',fullPage:false});
  }
  await gallery.getByRole('button',{name:'Next photograph',exact:true}).click();await active(0);
  await gallery.getByRole('button',{name:'Previous photograph',exact:true}).click();await active(2);
  await page.keyboard.press('ArrowLeft');await active(1);
  assert.equal(await gallery.getByRole('button',{name:'Play slideshow',exact:true}).getAttribute('aria-pressed'),'true','Interaction pauses autoplay');
  const sizes=await page.evaluate(()=>({width:innerWidth,scroll:document.documentElement.scrollWidth,heroHeight:document.querySelector('.mq-slideshow-hero').getBoundingClientRect().height,collectionTop:document.querySelector('#the-collection').getBoundingClientRect().top}));
  assert(sizes.scroll<=width+1,'No horizontal overflow');
  if(width>=1024)assert(sizes.heroHeight<620,'Compact desktop hero');
  if(width===1366)assert(sizes.collectionTop<768,'Collection begins within laptop viewport');
  if(width<768){const c=await hero.locator('.mq-premium-copy').boundingBox(),m=await gallery.boundingBox();assert(c.y+c.height<=m.y,'Mobile text above images');}
  report.checks.push({width,lineCounts,...sizes});console.log('Hero passed',width,JSON.stringify(sizes));await page.close();
 }
 // Automatic rotation is separate from keyboard/hover interactions.
 const auto=await context.newPage();await auto.setViewportSize({width:1366,height:768});
 await auto.goto(base+'?hero=autoplay-090',{waitUntil:'load',timeout:90000});await auto.locator('.mq-hero-controls').waitFor();
 await auto.waitForFunction(()=>document.querySelectorAll('.mq-hero-slide')[1].hidden===false,{},{timeout:12000});
 await auto.locator('.mq-hero-pause').click();const paused=await auto.locator('.mq-hero-index').innerText();
 await auto.waitForTimeout(7400);assert.equal(await auto.locator('.mq-hero-index').innerText(),paused,'Pause holds slide');
 await auto.close();
 const reduced=await context.newPage();await reduced.emulateMedia({reducedMotion:'reduce'});await reduced.goto(base+'?hero=reduced-090',{waitUntil:'load',timeout:90000});
 assert.equal(await reduced.locator('.mq-hero-pause').getAttribute('aria-pressed'),'true','Reduced motion starts paused');
 await reduced.waitForTimeout(7400);assert.equal(await reduced.locator('.mq-hero-index').innerText(),'01');await reduced.close();
 const plain=await browser.newContext({javaScriptEnabled:false});const p=await plain.newPage();await p.goto(base+'?hero=native-090',{waitUntil:'load',timeout:90000});
 assert(await p.locator('.mq-hero-slide').first().isVisible());assert(!(await p.locator('.mq-hero-controls').isVisible()));assert(await p.locator('.mq-premium-primary').isVisible());await plain.close();
 assert.equal(report.errors.length,0);report.passed=true;
 }finally{fs.writeFileSync(out+'/hero-carousel-report.json',JSON.stringify(report,null,2));await browser.close();}
})().catch(e=>{console.error(e);process.exitCode=1});
