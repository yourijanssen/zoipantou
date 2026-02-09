(function () {
  document.documentElement.classList.add('js-enabled');

  const sectionTabs = Array.from(document.querySelectorAll('.section-tab[href]'));
  const headerLinks = Array.from(document.querySelectorAll('.site-nav .menu a[href]'));
  const languageLinks = Array.from(document.querySelectorAll('.language-option[href]'));
  const siteHeader = document.querySelector('.site-header');
  const menuToggle = document.querySelector('.menu-toggle');
  const headerPanel = menuToggle
    ? document.getElementById(menuToggle.getAttribute('aria-controls'))
    : null;
  const mobileQuery = window.matchMedia('(max-width: 992px)');
  const languageScrollRestoreKey = 'zoipantou:language-scroll-restore';

  const normalizePath = (path) => (path || '/').replace(/\/+$/, '') || '/';
  const normalizeHash = (hash) => {
    if (!hash || hash.charAt(0) !== '#') {
      return '';
    }

    return '#' + decodeURIComponent(hash.slice(1));
  };

  const currentPath = normalizePath(window.location.pathname);

  const getHashForHrefOnCurrentPage = (href) => {
    if (!href) {
      return '';
    }

    if (href.charAt(0) === '#') {
      return normalizeHash(href);
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

  const resolveSectionByHash = (hash) => {
    const normalizedHash = normalizeHash(hash);
    if (!normalizedHash) {
      return null;
    }

    const id = normalizedHash.slice(1);
    if (!id) {
      return null;
    }

    return document.getElementById(id);
  };

  const getHashFromCurrentViewport = () => {
    const sectionHashes = sectionTabs
      .map((tab) => getHashForHrefOnCurrentPage(tab.getAttribute('href')))
      .filter(Boolean);

    if (!sectionHashes.length) {
      return '';
    }

    const cursor = window.scrollY + (siteHeader ? siteHeader.offsetHeight : 0) + Math.max(window.innerHeight * 0.3, 90);

    if (cursor < (resolveSectionByHash(sectionHashes[0])?.offsetTop || 0) - 20) {
      return '';
    }

    let activeHash = sectionHashes[0];
    sectionHashes.forEach((hash) => {
      const section = resolveSectionByHash(hash);
      if (section && section.offsetTop <= cursor) {
        activeHash = hash;
      }
    });

    return activeHash;
  };

  const readLanguageScrollRestore = () => {
    try {
      const raw = window.sessionStorage.getItem(languageScrollRestoreKey);
      if (!raw) {
        return null;
      }

      return JSON.parse(raw);
    } catch (e) {
      return null;
    }
  };

  const clearLanguageScrollRestore = () => {
    try {
      window.sessionStorage.removeItem(languageScrollRestoreKey);
    } catch (e) {
      // Ignore storage access errors.
    }
  };

  const writeLanguageScrollRestore = (payload) => {
    try {
      window.sessionStorage.setItem(languageScrollRestoreKey, JSON.stringify(payload));
    } catch (e) {
      // Ignore storage access errors.
    }
  };

  const applyLanguageScrollRestore = () => {
    const restore = readLanguageScrollRestore();
    if (!restore) {
      return;
    }

    const isTargetPath = restore.path === currentPath;
    const restoreTimestamp = Number(restore.timestamp || 0);
    const isExpired = restoreTimestamp > 0 && Date.now() - restoreTimestamp > 60000;
    if (!isTargetPath || isExpired) {
      clearLanguageScrollRestore();
      return;
    }

    const targetScroll = Number(restore.scrollY);
    if (Number.isFinite(targetScroll) && targetScroll >= 0) {
      const restorePosition = () => {
        window.scrollTo(0, targetScroll);
      };

      window.requestAnimationFrame(() => {
        restorePosition();
        window.setTimeout(restorePosition, 140);
      });
    }

    clearLanguageScrollRestore();
  };

  const setupLanguageSwitchPreservePosition = () => {
    if (!languageLinks.length) {
      return;
    }

    languageLinks.forEach((link) => {
      link.addEventListener('click', (event) => {
        let parsed;

        try {
          parsed = new URL(link.href, window.location.origin);
        } catch (e) {
          return;
        }

        writeLanguageScrollRestore({
          path: normalizePath(parsed.pathname),
          scrollY: window.scrollY,
          timestamp: Date.now(),
        });

        const currentHash = normalizeHash(window.location.hash);
        const viewportHash = getHashFromCurrentViewport();
        const contextHash = currentHash || viewportHash;
        if (contextHash) {
          parsed.hash = contextHash;
        }

        event.preventDefault();
        window.location.assign(parsed.toString());
      });
    });
  };

  setupLanguageSwitchPreservePosition();
  applyLanguageScrollRestore();

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

  const getLinkHashForCurrentPage = (link) => {
    const href = link.getAttribute('href');
    return getHashForHrefOnCurrentPage(href);
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
    const normalizedHash = normalizeHash(hash);
    if (!normalizedHash || seenHashes.has(normalizedHash) || !resolveSection(normalizedHash)) {
      return;
    }

    seenHashes.add(normalizedHash);
    sectionHashes.push(normalizedHash);
  };

  sectionTabs.forEach((tab) => collectHash(getLinkHashForCurrentPage(tab)));
  headerLinks.forEach((link) => collectHash(getLinkHashForCurrentPage(link)));

  const sectionAnchors = sectionHashes
    .map((hash) => ({
      hash,
      element: resolveSection(hash),
    }))
    .filter((entry) => entry.element)
    .sort((a, b) => a.element.offsetTop - b.element.offsetTop);

  if (!sectionAnchors.length) {
    return;
  }

  const getHeaderOffset = () => {
    return siteHeader ? siteHeader.offsetHeight : 0;
  };

  const setSectionTabsActive = (hash) => {
    const normalizedHash = normalizeHash(hash);
    let matched = false;

    sectionTabs.forEach((tab) => {
      const tabHash = getLinkHashForCurrentPage(tab);
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

      const linkHash = getLinkHashForCurrentPage(link);
      if (!linkHash) {
        link.removeAttribute('aria-current');
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

  const getScrollBasedHash = () => {
    const cursor = window.scrollY + getHeaderOffset() + Math.max(window.innerHeight * 0.3, 90);

    if (cursor < sectionAnchors[0].element.offsetTop - 20) {
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

  const visibilityByHash = new Map();

  const getObserverBasedHash = () => {
    let candidate = '';
    let bestRatio = 0;

    sectionAnchors.forEach((section) => {
      const ratio = visibilityByHash.get(section.hash) || 0;
      if (ratio <= 0) {
        return;
      }

      if (ratio > bestRatio) {
        candidate = section.hash;
        bestRatio = ratio;
      }
    });

    return candidate;
  };

  const getBestActiveHash = () => {
    const observerHash = getObserverBasedHash();
    return observerHash || getScrollBasedHash();
  };

  let sectionObserver = null;
  const setupSectionObserver = () => {
    if (typeof window.IntersectionObserver !== 'function') {
      return;
    }

    if (sectionObserver) {
      sectionObserver.disconnect();
    }

    const topMargin = -(getHeaderOffset() + 16);
    sectionObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          const hash = '#' + entry.target.id;
          visibilityByHash.set(hash, entry.isIntersecting ? entry.intersectionRatio : 0);
        });

        applyActiveState(getBestActiveHash());
      },
      {
        root: null,
        threshold: [0, 0.1, 0.25, 0.4, 0.6, 0.8],
        rootMargin: topMargin + 'px 0px -42% 0px',
      }
    );

    sectionAnchors.forEach((section) => {
      sectionObserver.observe(section.element);
    });
  };

  setupSectionObserver();

  const applyStateForCurrentPosition = () => {
    const currentHash = normalizeHash(window.location.hash);
    if (currentHash && resolveSection(currentHash)) {
      applyActiveState(currentHash);
      return;
    }

    applyActiveState(getBestActiveHash());
  };

  let ticking = false;
  window.addEventListener(
    'scroll',
    () => {
      if (ticking) {
        return;
      }

      ticking = true;
      window.requestAnimationFrame(() => {
        applyActiveState(getBestActiveHash());
        ticking = false;
      });
    },
    { passive: true }
  );

  window.addEventListener('resize', () => {
    setupSectionObserver();
    applyStateForCurrentPosition();
  });

  window.addEventListener('hashchange', applyStateForCurrentPosition);

  [...sectionTabs, ...headerLinks].forEach((link) => {
    const linkHash = getLinkHashForCurrentPage(link);
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
