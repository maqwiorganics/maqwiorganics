/* Native controls remain usable without JavaScript. */
(() => {
  const menu = document.querySelector('.mq-mobile-nav');
  if (menu) {
    const trigger = menu.querySelector('summary');
    menu.addEventListener('click', event => {
      if (event.target.closest('a')) menu.open = false;
    });
    document.addEventListener('click', event => {
      if (!menu.contains(event.target)) menu.open = false;
    });
    document.addEventListener('keydown', event => {
      if (event.key === 'Escape' && menu.open) {
        menu.open = false;
        trigger.focus();
      }
    });
    window.matchMedia('(min-width: 901px)').addEventListener('change', event => {
      if (event.matches) menu.open = false;
    });
  }
  const collectionPanel = hash => {
    const id = hash.slice(1);
    if (!/^collection-(honey|desi-ghee|achaar|itar)$/.test(id)) return null;
    const panel = document.getElementById(id)?.closest('details');
    return panel instanceof HTMLDetailsElement ? panel : null;
  };
  // Kadence may update the URL with history.pushState instead of hashchange.
  // Reveal content before its existing anchor handler calculates scroll position.
  document.addEventListener('click', event => {
    const anchor = event.target instanceof Element ? event.target.closest('a[href]') : null;
    if (!anchor) return;
    const url = new URL(anchor.href, window.location.href);
    if (url.origin !== location.origin || url.pathname !== location.pathname) return;
    if (anchor.classList.contains('mq-back-top') && url.hash === '#top') {
      event.preventDefault();
      event.stopPropagation();
      if (location.hash !== '#top') location.hash = 'top';
      window.scrollTo({ top: 0, left: 0, behavior: 'instant' });
      return;
    }
    const panel = collectionPanel(url.hash);
    if (panel) panel.open = true;
  }, true);
  const openCollection = () => {
    const panel = collectionPanel(window.location.hash);
    if (panel) {
      panel.open = true;
      panel.scrollIntoView({ block: 'start', behavior: 'instant' });
    }
  };
  window.addEventListener('hashchange', openCollection);
  window.addEventListener('pageshow', openCollection);
  openCollection();
})();

/* Lightweight campaign carousel; native first photograph remains without JS. */
(() => {
 const gallery=document.querySelector('.mq-hero-gallery');
 if(!gallery)return;
 const slides=[...gallery.querySelectorAll('.mq-hero-slide')];
 const controls=gallery.querySelector('.mq-hero-controls');
 if(slides.length<2||!controls)return;
 const dots=[...gallery.querySelectorAll('[data-slide]')],pause=gallery.querySelector('.mq-hero-pause');
 const names=['The collection','Honey & Ghee','Personal fragrance'];
 const motion=window.matchMedia('(prefers-reduced-motion: reduce)');
 let active=0,userPaused=motion.matches,hovering=false,visible=true,timer=null;
 const sync=()=>{
  clearInterval(timer);timer=null;
  pause.setAttribute('aria-pressed',String(userPaused));
  pause.setAttribute('aria-label',userPaused?'Play slideshow':'Pause slideshow');
  if(!userPaused&&!hovering&&visible&&!document.hidden)timer=setInterval(()=>show(active+1,false),7000);
 };
 const show=(next,manual=true)=>{
  active=(next+slides.length)%slides.length;
  slides.forEach((slide,i)=>{slide.hidden=i!==active;slide.classList.toggle('is-active',i===active);});
  dots.forEach((dot,i)=>{if(i===active)dot.setAttribute('aria-current','true');else dot.removeAttribute('aria-current');});
  gallery.querySelector('.mq-hero-index').textContent=String(active+1).padStart(2,'0');
  gallery.querySelector('.mq-hero-name').textContent=names[active];
  if(manual){userPaused=true;gallery.querySelector('.mq-hero-status').textContent=`${active+1} of ${slides.length}: ${names[active]}`;}
  sync();
 };
 controls.hidden=false;
 gallery.querySelector('.mq-hero-previous').addEventListener('click',()=>show(active-1));
 gallery.querySelector('.mq-hero-next').addEventListener('click',()=>show(active+1));
 dots.forEach(dot=>dot.addEventListener('click',()=>show(Number(dot.dataset.slide))));
 let pointerPauseState=null;
 pause.addEventListener('pointerdown',()=>{pointerPauseState=userPaused;});
 pause.addEventListener('pointercancel',()=>{pointerPauseState=null;});
 pause.addEventListener('click',()=>{userPaused=!(pointerPauseState??userPaused);pointerPauseState=null;sync();});
 gallery.addEventListener('focusin',()=>{userPaused=true;sync();});
 gallery.addEventListener('mouseenter',()=>{hovering=true;sync();});
 gallery.addEventListener('mouseleave',()=>{hovering=false;sync();});
 gallery.addEventListener('keydown',event=>{
  if(event.key==='ArrowRight'||event.key==='ArrowLeft'){event.preventDefault();show(active+(event.key==='ArrowRight'?1:-1));}
 });
 let touch=null;
 gallery.querySelector('.mq-premium-media').addEventListener('touchstart',event=>{const t=event.touches[0];touch={x:t.clientX,y:t.clientY};},{passive:true});
 gallery.querySelector('.mq-premium-media').addEventListener('touchend',event=>{
  if(!touch)return;const t=event.changedTouches[0],dx=t.clientX-touch.x,dy=t.clientY-touch.y;
  if(Math.abs(dx)>45&&Math.abs(dx)>Math.abs(dy)*1.5)show(active+(dx<0?1:-1));touch=null;
 },{passive:true});
 document.addEventListener('visibilitychange',sync);
 motion.addEventListener('change',event=>{if(event.matches){userPaused=true;sync();}});
 if('IntersectionObserver' in window)new IntersectionObserver(entries=>{visible=entries[0].isIntersecting;sync();},{threshold:.2}).observe(gallery);
 const warmImages=()=>slides.slice(1).forEach(slide=>{slide.querySelector('img').loading='eager';});
 if(document.readyState==='complete')warmImages();else window.addEventListener('load',warmImages,{once:true});
 sync();
})();
