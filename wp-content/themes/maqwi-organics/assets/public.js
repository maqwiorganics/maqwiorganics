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
  const openCollection = () => {
    const id = window.location.hash.slice(1);
    if (!/^collection-(honey|desi-ghee|achaar|itar)$/.test(id)) return;
    const panel = document.getElementById(id)?.closest('details');
    if (panel instanceof HTMLDetailsElement) {
      panel.open = true;
      panel.scrollIntoView({ block: 'start', behavior: 'instant' });
    }
  };
  window.addEventListener('hashchange', openCollection);
  window.addEventListener('pageshow', openCollection);
  openCollection();
})();
