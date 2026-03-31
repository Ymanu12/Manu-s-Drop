import './bootstrap';

const RECAPTCHA_CONFIG_URL = '/recaptcha/config';
const THEME_STORAGE_KEY = 'theme';
const DARK_CLASS = 'dark';

const loadScript = (src) => new Promise((resolve, reject) => {
    const existing = document.querySelector(`script[src="${src}"]`);
    if (existing) {
        if (existing.dataset.loaded === '1') {
            resolve();
            return;
        }
        existing.addEventListener('load', () => resolve(), { once: true });
        existing.addEventListener('error', () => reject(new Error('Failed to load script.')), { once: true });
        return;
    }

    const script = document.createElement('script');
    script.src = src;
    script.async = true;
    script.defer = true;
    script.addEventListener('load', () => {
        script.dataset.loaded = '1';
        resolve();
    }, { once: true });
    script.addEventListener('error', () => reject(new Error('Failed to load script.')), { once: true });
    document.head.appendChild(script);
});

const getEffectiveMethod = (form) => {
    const method = (form.getAttribute('method') || 'GET').toUpperCase();
    const spoofedMethod = (form.querySelector('input[name="_method"]')?.value || '').toUpperCase();
    return spoofedMethod || method;
};

const needsRecaptcha = (form) => {
    if (!form || form.dataset.recaptchaIgnore === '1') {
        return false;
    }

    return ['POST', 'PUT', 'PATCH', 'DELETE'].includes(getEffectiveMethod(form));
};

const ensureTokenField = (form) => {
    let input = form.querySelector('input[name="g-recaptcha-response"]');
    if (!input) {
        input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'g-recaptcha-response';
        form.appendChild(input);
    }
    return input;
};

const showRecaptchaError = () => {
    window.alert('La verification reCAPTCHA a echoue. Merci de reessayer.');
};

const initRecaptchaV3 = async () => {
    const response = await fetch(RECAPTCHA_CONFIG_URL, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
        credentials: 'same-origin',
    });

    if (!response.ok) {
        return;
    }

    const config = await response.json();
    if (!config.enabled || !config.siteKey) {
        return;
    }

    await loadScript(`https://www.google.com/recaptcha/api.js?render=${encodeURIComponent(config.siteKey)}`);
    if (typeof window.grecaptcha === 'undefined') {
        return;
    }

    const nativeSubmit = HTMLFormElement.prototype.submit;
    const trackedForms = new WeakSet();

    const executeRecaptcha = (form) => {
        const action = form.dataset.recaptchaAction || 'submit';
        const tokenField = ensureTokenField(form);

        return window.grecaptcha.execute(config.siteKey, { action }).then((token) => {
            tokenField.value = token;
            form.dataset.recaptchaVerified = '1';
            form.dataset.recaptchaPending = '0';
        });
    };

    const submitWithRecaptcha = (form) => {
        if (!needsRecaptcha(form)) {
            nativeSubmit.call(form);
            return;
        }

        if (form.dataset.recaptchaVerified === '1') {
            nativeSubmit.call(form);
            return;
        }

        if (form.dataset.recaptchaPending === '1') {
            return;
        }

        form.dataset.recaptchaPending = '1';

        window.grecaptcha.ready(() => {
            executeRecaptcha(form)
                .then(() => nativeSubmit.call(form))
                .catch(() => {
                    form.dataset.recaptchaPending = '0';
                    showRecaptchaError();
                });
        });
    };

    const bindForm = (form) => {
        if (!needsRecaptcha(form) || trackedForms.has(form)) {
            return;
        }

        trackedForms.add(form);
        ensureTokenField(form);

        form.addEventListener('submit', (event) => {
            if (form.dataset.recaptchaVerified === '1') {
                form.dataset.recaptchaVerified = '0';
                form.dataset.recaptchaPending = '0';
                return;
            }

            event.preventDefault();
            submitWithRecaptcha(form);
        });
    };

    document.querySelectorAll('form').forEach(bindForm);

    new MutationObserver(() => {
        document.querySelectorAll('form').forEach(bindForm);
    }).observe(document.body, { childList: true, subtree: true });

    HTMLFormElement.prototype.submit = function submitOverride() {
        if (!needsRecaptcha(this)) {
            return nativeSubmit.call(this);
        }

        submitWithRecaptcha(this);
    };
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        initThemeToggle();
        initRecaptchaV3().catch(() => undefined);
    }, { once: true });
} else {
    initThemeToggle();
    initRecaptchaV3().catch(() => undefined);
}

function initThemeToggle() {
    const root = document.documentElement;
    const userTheme = root.dataset.userTheme || 'light';
    const savedTheme = window.localStorage.getItem(THEME_STORAGE_KEY);
    const initialTheme = savedTheme || userTheme || 'light';

    applyTheme(initialTheme);

    document.querySelectorAll('[data-theme-toggle]').forEach((toggle) => {
        syncThemeToggle(toggle, initialTheme);

        toggle.addEventListener('click', async (event) => {
            event.preventDefault();

            const currentTheme = root.classList.contains(DARK_CLASS) ? 'dark' : 'light';
            const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';

            applyTheme(nextTheme);
            window.localStorage.setItem(THEME_STORAGE_KEY, nextTheme);

            const endpoint = root.dataset.themeUpdateUrl;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!endpoint || !csrfToken) {
                return;
            }

            try {
                await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ theme: nextTheme }),
                });
            } catch (error) {
                // Keep local preference even if sync fails.
            }
        });
    });
}

function applyTheme(theme) {
    const root = document.documentElement;
    const metaThemeColor = document.querySelector('meta[name="theme-color"]');
    const normalizedTheme = theme === 'dark' ? 'dark' : 'light';

    root.classList.toggle(DARK_CLASS, normalizedTheme === 'dark');
    root.setAttribute('data-theme', normalizedTheme);
    root.style.colorScheme = normalizedTheme;

    document.querySelectorAll('[data-theme-toggle]').forEach((toggle) => {
        syncThemeToggle(toggle, normalizedTheme);
    });

    if (metaThemeColor) {
        metaThemeColor.setAttribute('content', normalizedTheme === 'dark' ? '#111827' : '#ffffff');
    }
}

function syncThemeToggle(toggle, theme) {
    const isDark = theme === 'dark';

    toggle.setAttribute('aria-pressed', String(isDark));
    toggle.setAttribute('title', isDark ? 'Activer le mode clair' : 'Activer le mode sombre');
}

function showWishlistToast(message, variant = 'success') {
    let container = document.querySelector('[data-wishlist-toast-container]');

    if (!container) {
        container = document.createElement('div');
        container.dataset.wishlistToastContainer = '1';
        container.style.position = 'fixed';
        container.style.right = '1rem';
        container.style.bottom = '1rem';
        container.style.zIndex = '1080';
        container.style.display = 'grid';
        container.style.gap = '.75rem';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.textContent = message;
    toast.style.minWidth = '240px';
    toast.style.maxWidth = '320px';
    toast.style.padding = '.85rem 1rem';
    toast.style.borderRadius = '.9rem';
    toast.style.border = variant === 'error' ? '1px solid rgba(239, 68, 68, .28)' : '1px solid rgba(15, 23, 42, .08)';
    toast.style.background = variant === 'error' ? 'rgba(127, 29, 29, .96)' : 'rgba(15, 23, 42, .96)';
    toast.style.color = '#fff';
    toast.style.boxShadow = '0 10px 30px rgba(15, 23, 42, .18)';
    toast.style.fontSize = '.92rem';
    toast.style.lineHeight = '1.45';
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(10px)';
    toast.style.transition = 'opacity .2s ease, transform .2s ease';

    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
    });

    window.setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        window.setTimeout(() => toast.remove(), 220);
    }, 2200);
}

function initWishlistToggles() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!csrfToken) {
        return;
    }

    const updateWishlistCounters = (count) => {
        document.querySelectorAll('.js-wishlist-count').forEach((node) => {
            node.textContent = count;
        });

        const desktopLink = document.querySelector('.header-tools__item--wishlist');
        const mobileWrap = document.querySelector('.footer-mobile__link--wishlist .position-relative');

        const ensureBadge = (container, className) => {
            if (!container) return null;
            let badge = container.querySelector('.js-wishlist-count');
            if (!badge && count > 0) {
                badge = document.createElement('span');
                badge.className = className;
                container.appendChild(badge);
            }
            return badge;
        };

        const desktopBadge = ensureBadge(desktopLink, 'cart-amount d-block position-absolute js-wishlist-count');
        const mobileBadge = ensureBadge(mobileWrap, 'wishlist-amount d-block position-absolute js-wishlist-count');

        [desktopBadge, mobileBadge].forEach((badge) => {
            if (!badge) return;
            if (count > 0) {
                badge.textContent = count;
            } else {
                badge.remove();
            }
        });
    };

    const setButtonState = (form, active, payload = {}) => {
        const button = form.querySelector('.wishlist-icon-btn');
        if (!button) {
            return;
        }

        const productId = payload.product_id || form.dataset.productId || form.querySelector('input[name="id"]')?.value;
        const addUrl = payload.add_url || form.dataset.addUrl || '/wishlist/add';
        const removeUrl = payload.remove_url || form.dataset.removeUrl || form.action;

        form.dataset.productId = String(productId || '');
        form.dataset.addUrl = addUrl;
        form.dataset.removeUrl = removeUrl;

        button.classList.toggle('is-active', active);
        button.title = active ? 'Remove To Wishlist' : 'Add To Wishlist';

        if (active) {
            form.action = removeUrl;
            form.setAttribute('method', 'POST');
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'DELETE';
        } else {
            form.action = addUrl;
            form.setAttribute('method', 'POST');
            form.querySelector('input[name="_method"]')?.remove();
            let idInput = form.querySelector('input[name="id"]');
            if (!idInput) {
                idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'id';
                form.appendChild(idInput);
            }
            idInput.value = productId || '';
        }
    };

    document.addEventListener('submit', async (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement) || form.dataset.wishlistToggle !== '1') {
            return;
        }

        event.preventDefault();

        const button = form.querySelector('.wishlist-icon-btn');
        if (!button || form.dataset.pending === '1') {
            return;
        }

        form.dataset.pending = '1';
        button.disabled = true;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                credentials: 'same-origin',
                body: new FormData(form),
            });

            if (!response.ok) {
                throw new Error('Wishlist request failed');
            }

            const payload = await response.json();
            const active = Boolean(payload.remove_url);
            setButtonState(form, active, payload);
            updateWishlistCounters(payload.wishlist_count || 0);
            showWishlistToast(payload.message || (active ? 'Produit ajoute a la wishlist.' : 'Produit retire de la wishlist.'));
        } catch (error) {
            showWishlistToast('Impossible de mettre a jour la wishlist pour le moment.', 'error');
            window.setTimeout(() => {
                window.location.assign(window.location.href);
            }, 500);
        } finally {
            form.dataset.pending = '0';
            button.disabled = false;
        }
    });
}

initWishlistToggles();
