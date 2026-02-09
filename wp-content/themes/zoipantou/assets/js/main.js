(function () {
  document.documentElement.classList.add('js-enabled');

  const sectionTabs = Array.from(document.querySelectorAll('.section-tab[href^="#"]'));
  const headerLinks = Array.from(document.querySelectorAll('.site-nav .menu a'));
  const siteHeader = document.querySelector('.site-header');
  const menuToggle = document.querySelector('.menu-toggle');
  const headerPanel = menuToggle
    ? document.getElementById(menuToggle.getAttribute('aria-controls'))
    : null;
  const mobileQuery = window.matchMedia('(max-width: 992px)');

  const normalizePath = (path) => (path || '/').replace(/\/+$/, '') || '/';
  const normalizeHash = (hash) => {
    if (!hash || hash.charAt(0) !== '#') {
      return '';
    }
    return '#' + decodeURIComponent(hash.slice(1));
  };

  const currentPath = normalizePath(window.location.pathname);

  const setMenuOpen = (isOpen) => {
    if (!siteHeader || !menuToggle || !headerPanel) {
      return;
    }

    siteHeader.classList.toggle('is-menu-open', isOpen);
    menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

    if (isOpen) {
      headerPanel.removeAttribute('hidden');
    } else if (mobileQuery.matches) {
      headerPanel.setAttribute('hidden', '');
    }
  };

  if (siteHeader && menuToggle && headerPanel) {
    if (mobileQuery.matches) {
      headerPanel.setAttribute('hidden', '');
    }

    menuToggle.addEventListener('click', () => {
      const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
      setMenuOpen(!isOpen);
    });

    window.addEventListener('resize', () => {
      if (!mobileQuery.matches) {
        setMenuOpen(false);
        headerPanel.removeAttribute('hidden');
        return;
      }

      if (!siteHeader.classList.contains('is-menu-open')) {
        headerPanel.setAttribute('hidden', '');
      }
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        setMenuOpen(false);
      }
    });

    headerLinks.forEach((link) => {
      link.addEventListener('click', () => {
        if (mobileQuery.matches) {
          setMenuOpen(false);
        }
      });
    });
  }

  const getHashForCurrentPage = (link) => {
    const href = link.getAttribute('href');
    if (!href) {
      return '';
    }

    let parsed;
    try {
      parsed = new URL(href, window.location.origin);
    } catch (e) {
      return '';
    }

    if (normalizePath(parsed.pathname) !== currentPath) {
      return '';
    }

    return normalizeHash(parsed.hash);
  };

  const isHomeLinkForCurrentPage = (link) => {
    const href = link.getAttribute('href');
    if (!href) {
      return false;
    }

    let parsed;
    try {
      parsed = new URL(href, window.location.origin);
    } catch (e) {
      return false;
    }

    return normalizePath(parsed.pathname) === currentPath && !parsed.hash;
  };

  const resolveSection = (hash) => {
    if (!hash) {
      return null;
    }

    const id = hash.slice(1);
    if (!id) {
      return null;
    }

    return document.getElementById(id);
  };

  const sectionHashes = [];
  const seenHashes = new Set();

  const collectHash = (hash) => {
    if (!hash || seenHashes.has(hash) || !resolveSection(hash)) {
      return;
    }
    seenHashes.add(hash);
    sectionHashes.push(hash);
  };

  sectionTabs.forEach((tab) => collectHash(normalizeHash(tab.getAttribute('href'))));
  headerLinks.forEach((link) => collectHash(getHashForCurrentPage(link)));

  const sectionAnchors = sectionHashes.map((hash) => ({
    hash,
    element: resolveSection(hash),
  })).filter((entry) => entry.element)
    .sort((a, b) => a.element.offsetTop - b.element.offsetTop);

  if (!sectionAnchors.length) {
    return;
  }

  const setSectionTabsActive = (hash) => {
    const normalizedHash = normalizeHash(hash);
    let matched = false;

    sectionTabs.forEach((tab) => {
      const tabHash = normalizeHash(tab.getAttribute('href'));
      const isActive = !!normalizedHash && tabHash === normalizedHash;
      tab.classList.toggle('is-active', isActive);
      matched = matched || isActive;
    });

    if (!matched && sectionTabs[0]) {
      sectionTabs[0].classList.add('is-active');
    }
  };

  const setHeaderActive = (hash) => {
    const normalizedHash = normalizeHash(hash);
    let matchedSectionItem = false;

    headerLinks.forEach((link) => {
      const menuItem = link.closest('.menu-item, .page_item');
      if (!menuItem) {
        return;
      }

      const linkHash = getHashForCurrentPage(link);
      if (!linkHash) {
        return;
      }

      const isActive = !!normalizedHash && linkHash === normalizedHash;
      menuItem.classList.toggle('current-menu-item', isActive);
      menuItem.classList.toggle('current_page_item', isActive);
      if (isActive) {
        link.setAttribute('aria-current', 'page');
        matchedSectionItem = true;
      } else {
        link.removeAttribute('aria-current');
      }
    });

    headerLinks.forEach((link) => {
      const menuItem = link.closest('.menu-item, .page_item');
      if (!menuItem || !isHomeLinkForCurrentPage(link)) {
        return;
      }

      const isActiveHome = !matchedSectionItem;
      menuItem.classList.toggle('current-menu-item', isActiveHome);
      menuItem.classList.toggle('current_page_item', isActiveHome);
      if (isActiveHome) {
        link.setAttribute('aria-current', 'page');
      } else {
        link.removeAttribute('aria-current');
      }
    });
  };

  const applyActiveState = (hash) => {
    setSectionTabsActive(hash);
    setHeaderActive(hash);
  };

  const getActiveHashFromScroll = () => {
    const header = document.querySelector('.site-header');
    const offset = (header ? header.offsetHeight : 0) + 24;
    const cursor = window.scrollY + offset;

    if (cursor < sectionAnchors[0].element.offsetTop - 40) {
      return '';
    }

    let activeHash = sectionAnchors[0].hash;
    sectionAnchors.forEach((section) => {
      if (section.element.offsetTop <= cursor) {
        activeHash = section.hash;
      }
    });

    return activeHash;
  };

  const applyStateForCurrentPosition = () => {
    const currentHash = normalizeHash(window.location.hash);
    if (currentHash && resolveSection(currentHash)) {
      applyActiveState(currentHash);
      return;
    }

    applyActiveState(getActiveHashFromScroll());
  };

  let ticking = false;
  window.addEventListener('scroll', () => {
    if (ticking) {
      return;
    }

    ticking = true;
    window.requestAnimationFrame(() => {
      applyActiveState(getActiveHashFromScroll());
      ticking = false;
    });
  }, { passive: true });

  window.addEventListener('hashchange', applyStateForCurrentPosition);

  [...sectionTabs, ...headerLinks].forEach((link) => {
    const linkHash = normalizeHash(link.getAttribute('href'));
    if (!linkHash) {
      return;
    }

    link.addEventListener('click', () => {
      window.setTimeout(() => {
        applyActiveState(linkHash);
      }, 0);
    });
  });

  applyStateForCurrentPosition();
})();
