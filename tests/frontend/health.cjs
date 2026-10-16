/** Small read-only availability check. Never submits a form or an order. */
const {chromium}=require('playwright');
const fs=require('node:fs');
const assert=require('node:assert/strict');
const out='health-report',base='https://maqwiorganics.gt.tc/';
fs.mkdirSync(out,{recursive:true});
(async()=>{
 const report={checkedAt:new Date().toISOString(),checks:[],errors:[],passed:false};
 const browser=await chromium.launch({executablePath:['/usr/bin/google-chrome','/usr/bin/chromium'].find(fs.existsSync)});
 const context=await browser.newContext({viewport:{width:390,height:844}});
 const page=await context.newPage();
 page.on('pageerror',e=>report.errors.push(e.message));
 try{
  for(const [route,marker,price] of [
   ['', '.mq-premium-hero',null],['shop/','ul.products>li.product',null],
   ['product/maqwi-honey/','.summary','4,500'],
   ['product/maqwi-desi-ghee/','.summary','3,500'],
   ['product/maqwi-itar/','.summary','200'],
   ['product/maqwi-achaar/','.summary',null]
  ]){
   let response=await page.goto(base+route,{waitUntil:'domcontentloaded',timeout:45000});
   // One retry is reserved for a transient server error, never a security challenge.
   const text=await page.locator('body').innerText();
   if(/verify you are human|checking your browser|confirm you.re not a robot|unusual traffic/i.test(text)){
    throw new Error('Human verification blocks the check; availability is unverified.');
   }
   if(response&&[502,503,504].includes(response.status())){
    await page.waitForTimeout(5000);
    response=await page.reload({waitUntil:'domcontentloaded',timeout:45000});
   }
   assert(response&&response.status()===200,'HTTP failure at '+route);
   assert.equal(new URL(page.url()).origin,new URL(base).origin,'Unexpected destination');
   await page.locator('.mq-header').waitFor({timeout:15000});
   await page.locator(marker).first().waitFor({timeout:15000});
   assert.equal(await page.locator('main h1').count(),1,'Missing or duplicate page heading');
   if(route==='shop/'){assert.equal(await page.locator(marker).count(),4,'Missing catalogue products');}
   if(price){assert((await page.locator('.summary').innerText()).includes(price),'Approved price missing');}
   const overflow=await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth+1);
   assert(!overflow,'Mobile horizontal overflow');
   report.checks.push({route:route||'home',status:response.status(),passed:true});
  }
  const api=await page.request.get(base+'?rest_route=/wc/store/v1/checkout',{timeout:20000});
  assert.equal(api.status(),403,'Closed-order guard missing');
  assert.equal((await api.json()).code,'maqwi_orders_closed','Unexpected checkout response');
  report.checks.push({route:'checkout guard (GET only)',status:403,passed:true});
  assert.equal(report.errors.length,0,'Frontend JavaScript errors');
  report.passed=true;
 } catch(error){
  report.errors.push(error.message);
  await page.screenshot({path:out+'/public-failure.png',fullPage:true}).catch(()=>{});
  process.exitCode=1;
 } finally {
  fs.writeFileSync(out+'/public.json',JSON.stringify(report,null,2));
  console.log('MAQWI_PUBLIC_HEALTH='+JSON.stringify(report));
  await browser.close();
 }
})().catch(error=>{console.error(error);process.exitCode=1;});
