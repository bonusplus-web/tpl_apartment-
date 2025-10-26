const select = (selector, scope = document) => scope.querySelector(selector);
const selectAll = (selector, scope = document) => Array.from(scope.querySelectorAll(selector));

const toggleClass = (el, className = 'active') => {
  if (!el) return;
  el.classList.toggle(className);
};

const navbar = select('[data-navbar]');
const overlay = select('[data-overlay]');
const navCloseBtn = select('[data-nav-close-btn]');
const navOpenBtn = select('[data-nav-open-btn]');
const navbarLinks = selectAll('[data-nav-link]');

const toggleNavbar = () => {
  toggleClass(navbar);
  toggleClass(overlay);
  document.body.classList.toggle('nav-open');
};

[navCloseBtn, navOpenBtn, overlay, ...navbarLinks].forEach((el) => {
  if (!el) return;
  el.addEventListener('click', () => toggleNavbar());
});

const header = select('[data-header]');
if (header) {
  const setHeaderState = () => {
    if (window.scrollY >= 400) {
      header.classList.add('active');
    } else {
      header.classList.remove('active');
    }
  };
  window.addEventListener('scroll', setHeaderState, { passive: true });
  setHeaderState();
}

const langSwitch = select('[data-lang-switch]');
if (langSwitch) {
  const toggle = select('.lang-switch__toggle', langSwitch);
  const menu = select('.lang-menu', langSwitch);
  const closeMenu = () => {
    menu?.classList.remove('open');
    toggle?.setAttribute('aria-expanded', 'false');
  };
  toggle?.addEventListener('click', (event) => {
    event.preventDefault();
    const isOpen = menu?.classList.toggle('open');
    toggle?.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
  });
  document.addEventListener('click', (event) => {
    if (!langSwitch.contains(event.target)) {
      closeMenu();
    }
  });
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      closeMenu();
    }
  });
}

const contactForm = select('.contact-form');
if (contactForm) {
  const statusNode = select('.form-status', contactForm);
  const setStatus = (message, isError = false) => {
    if (!statusNode) return;
    statusNode.textContent = message || '';
    statusNode.classList.toggle('is-error', Boolean(isError));
    if (message) {
      statusNode.focus?.();
    }
  };

  contactForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    setStatus('');

    const formData = new FormData(contactForm);
    try {
      const response = await fetch(contactForm.action, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      const result = await response.json();
      if (result.success) {
        setStatus(result.message || '');
        contactForm.reset();
        const tokenField = select('input[name="token"]', contactForm);
        const startField = select('input[name="started_at"]', contactForm);
        if (tokenField && result.token) {
          tokenField.value = result.token;
        }
        if (startField && result.started_at) {
          startField.value = result.started_at;
        }
      } else {
        setStatus(result.message || 'An error occurred', true);
      }
    } catch (error) {
      console.error(error);
      setStatus(window.__contactCopy?.error || 'An error occurred', true);
    }
  });
}

selectAll('img[data-src]').forEach((img) => {
  const observer = new IntersectionObserver((entries, observerInstance) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const target = entry.target;
        target.src = target.dataset.src;
        observerInstance.unobserve(target);
      }
    });
  });
  observer.observe(img);
});
