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
    window.matchMedia('(min-width: 640px)').addEventListener('change', event => {
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
