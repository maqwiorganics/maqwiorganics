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

/* Automatic campaign scenes: text and image share one source of truth. */
(() => {
 const hero=document.querySelector('.mq-slideshow-hero');if(!hero)return;
 const slides=[...hero.querySelectorAll('.mq-hero-slide')];if(slides.length<2)return;
 const title=hero.querySelector('#mq-premium-title'),description=hero.querySelector('.mq-premium-description');
 const primary=hero.querySelector('.mq-premium-primary'),label=primary.querySelector('.mq-primary-label'),copy=hero.querySelector('.mq-premium-copy');
 const motion=window.matchMedia('(prefers-reduced-motion: reduce)');
 let active=0,visible=true,focused=false,stopped=false,timer=null;
 const sync=()=>{
  clearInterval(timer);timer=null;
  if(!motion.matches&&!focused&&!stopped&&visible&&!document.hidden)timer=setInterval(showNext,7000);
 };
 const showNext=()=>{
  active=(active+1)%slides.length;const slide=slides[active];
  // One synchronous update keeps the copy, destination and image aligned.
  const lines=JSON.parse(slide.dataset.headline);
  title.querySelectorAll('.mq-hero-line').forEach((line,i)=>{line.textContent=lines[i];});
  description.textContent=slide.dataset.description;
  label.textContent=slide.dataset.cta;primary.setAttribute('href',slide.dataset.url);
  slides.forEach((item,i)=>{item.hidden=i!==active;item.classList.toggle('is-active',i===active);});
  copy.classList.remove('mq-copy-arriving');void copy.offsetWidth;copy.classList.add('mq-copy-arriving');
 };
 hero.addEventListener('focusin',event=>{if(event.target===hero&&!hero.matches(':focus-visible'))return;focused=true;sync();});
 hero.addEventListener('focusout',event=>{if(!hero.contains(event.relatedTarget)){focused=false;sync();}});
 hero.addEventListener('keydown',event=>{if(event.key==='Escape'){stopped=true;sync();}});
 document.addEventListener('visibilitychange',sync);motion.addEventListener('change',sync);
 if('IntersectionObserver' in window)new IntersectionObserver(entries=>{visible=entries[0].isIntersecting;sync();},{threshold:.2}).observe(hero);
 const warmImages=()=>slides.slice(1).forEach(slide=>{slide.querySelector('img').loading='eager';});
 if(document.readyState==='complete')warmImages();else window.addEventListener('load',warmImages,{once:true});
 sync();
})();
