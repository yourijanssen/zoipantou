(function () {
  document.documentElement.classList.add('js-enabled');

  const tabs = Array.from(document.querySelectorAll('.section-tab[href^="#"]'));

  if (!tabs.length) {
    return;
  }

  const setActiveTab = (targetHash) => {
    const normalizedHash = targetHash && targetHash.startsWith('#') ? targetHash : '';
    let matched = false;

    tabs.forEach((tab) => {
      const isActive = tab.getAttribute('href') === normalizedHash;
      tab.classList.toggle('is-active', isActive);
      matched = matched || isActive;
    });

    if (!matched && tabs[0]) {
      tabs[0].classList.add('is-active');
    }
  };

  const initialHash = window.location.hash || tabs[0].getAttribute('href');
  setActiveTab(initialHash);

  tabs.forEach((tab) => {
    tab.addEventListener('click', () => {
      const targetHash = tab.getAttribute('href');
      setActiveTab(targetHash);
    });
  });

  window.addEventListener('hashchange', () => {
    setActiveTab(window.location.hash);
  });
})();
