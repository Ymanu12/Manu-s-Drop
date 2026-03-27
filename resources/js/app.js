import './bootstrap';

const RECAPTCHA_CONFIG_URL = '/recaptcha/config';

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
        initRecaptchaV3().catch(() => undefined);
    }, { once: true });
} else {
    initRecaptchaV3().catch(() => undefined);
}
