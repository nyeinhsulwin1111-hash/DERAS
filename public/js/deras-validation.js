/**
 * DERAS global frontend form validation.
 * - Red border + red error text under invalid fields
 * - Supports: required, email, number min/max, minlength/maxlength,
 *   pattern, phone, password confirmation
 */
(function () {
    'use strict';

    var MSG = {
        required: 'ဖြည့်သွင်းရန် လိုအပ်ပါသည်။',
        email: 'အီးမေးလ်ပုံစံ မှန်ကန်စွာ ဖြည့်ပါ။',
        number: 'ဂဏန်းသာ ဖြည့်သွင်းပါ။',
        numberRequired: 'အနည်းဆုံး 0 ဖြည့်ပါ။',
        min: function (n) { return 'အနည်းဆုံးတန်ဖိုးမှာ ' + n + ' ဖြစ်ရပါမည်။'; },
        max: function (n) { return 'အများဆုံးတန်ဖိုးမှာ ' + n + ' ဖြစ်ရပါမည်။'; },
        minlength: function (n) { return 'အနည်းဆုံး ' + n + ' လုံး ဖြည့်ပါ။'; },
        maxlength: function (n) { return 'အများဆုံး ' + n + ' လုံးသာ ခွင့်ပြုပါသည်။'; },
        pattern: 'ပုံစံမှန်ကန်စွာ ဖြည့်သွင်းပါ။',
        phone: 'ဖုန်းနံပါတ် မှန်ကန်စွာ ဖြည့်ပါ။',
        passwordMatch: 'စကားဝှက်နှင့် မကိုက်ညီပါ။',
        select: 'ရွေးချယ်ရန် လိုအပ်ပါသည်။'
    };

    function isValidateableForm(form) {
        if (!(form instanceof HTMLFormElement)) return false;
        if (form.hasAttribute('data-no-validate')) return false;
        if ((form.getAttribute('method') || 'get').toLowerCase() === 'get') return false;

        var methodInput = form.querySelector('input[name="_method"]');
        if (methodInput && String(methodInput.value).toUpperCase() === 'DELETE') return false;

        var action = (form.getAttribute('action') || '').toLowerCase();
        if (action.indexOf('logout') !== -1) return false;

        return true;
    }

    function fieldLabel(el) {
        if (el.getAttribute('data-label')) return el.getAttribute('data-label');
        if (el.id) {
            var lab = document.querySelector('label[for="' + el.id + '"]');
            if (lab) return lab.textContent.replace(/\s+/g, ' ').trim();
        }
        var wrap = el.closest('.mb-3, .mb-4, .form-section, .col-md-3, .col-md-4, .col-md-6, .col-md-12, .col-12, div');
        if (wrap) {
            var near = wrap.querySelector('label.form-label, label');
            if (near) return near.textContent.replace(/\s+/g, ' ').trim();
        }
        return el.getAttribute('placeholder') || el.name || 'အကွက်';
    }

    function clearFieldError(el) {
        el.classList.remove('deras-field-invalid', 'is-invalid');
        el.removeAttribute('aria-invalid');
        var parent = el.parentElement;
        if (!parent) return;

        var kids = parent.children;
        for (var i = kids.length - 1; i >= 0; i--) {
            if (kids[i].classList && kids[i].classList.contains('deras-field-error')) {
                parent.removeChild(kids[i]);
            }
        }

        var sib = el.nextElementSibling;
        while (sib) {
            var next = sib.nextElementSibling;
            if (sib.classList && sib.classList.contains('deras-field-error')) {
                sib.remove();
                break;
            }
            if (sib.classList && (sib.classList.contains('password-toggle-icon') || sib.tagName === 'I')) {
                sib = next;
                continue;
            }
            break;
        }
    }

    function showFieldError(el, message) {
        clearFieldError(el);
        el.classList.add('deras-field-invalid', 'is-invalid');
        el.setAttribute('aria-invalid', 'true');

        var err = document.createElement('div');
        err.className = 'deras-field-error';
        err.textContent = message;

        // Prefer insert after the field, or after password wrapper
        var anchor = el;
        var relativeWrap = el.closest('[style*="position: relative"], .position-relative');
        if (relativeWrap && relativeWrap.contains(el) && relativeWrap.parentElement) {
            anchor = relativeWrap;
        }
        if (anchor.parentNode) {
            if (anchor.nextSibling) {
                anchor.parentNode.insertBefore(err, anchor.nextSibling);
            } else {
                anchor.parentNode.appendChild(err);
            }
        }
    }

    function isEmpty(el) {
        if (el.tagName === 'SELECT') {
            return !el.value || el.value === '';
        }
        var type = (el.getAttribute('type') || '').toLowerCase();
        if (type === 'number') {
            // Cleared / incomplete number inputs must be treated as empty
            if (el.validity && el.validity.badInput) return true;
            var raw = String(el.value == null ? '' : el.value).trim();
            if (raw === '') return true;
            if (typeof el.valueAsNumber === 'number' && Number.isNaN(el.valueAsNumber)) return true;
            return false;
        }
        return String(el.value == null ? '' : el.value).trim() === '';
    }

    function isOptionalEmptyPassword(el) {
        // Edit profile password fields: optional when empty
        if (el.type !== 'password') return false;
        if (el.name !== 'password' && el.name !== 'password_confirmation') return false;
        if (el.hasAttribute('required')) return false;
        return isEmpty(el);
    }

    function isSkippedReadonly(el) {
        // Permanent auto-calc fields are readonly; skip those.
        // Password fields often use temporary readonly anti-autofill — still validate them.
        var type = (el.getAttribute('type') || '').toLowerCase();
        if (type === 'password') return false;
        return !!(el.readOnly || el.hasAttribute('readonly'));
    }

    function isRemarkOrOptional(el) {
        var name = (el.name || '').toLowerCase();
        // Display-only fields (no name) — skip
        if (!name) return true;
        if (name === 'remark' || name === 'remarks' || name === 'note' || name === 'notes') return true;
        if (el.dataset.optional === '1' || el.hasAttribute('data-optional')) return true;
        return false;
    }

    function isNonTextControl(el) {
        var type = (el.getAttribute('type') || '').toLowerCase();
        return type === 'checkbox' || type === 'radio' || type === 'file'
            || type === 'image' || type === 'range' || type === 'color';
    }

    function validateField(el) {
        if (el.disabled) return null;
        if (isSkippedReadonly(el)) return null;
        if (isRemarkOrOptional(el)) return null;
        if (el.type === 'hidden') return null;
        if (el.type === 'submit' || el.type === 'button' || el.type === 'reset') return null;
        if (el.name === '_token' || el.name === '_method') return null;

        var type = (el.getAttribute('type') || el.tagName).toLowerCase();
        var name = el.name || '';
        var value = String(el.value == null ? '' : el.value).trim();

        // Optional empty password on edit screens
        if (isOptionalEmptyPassword(el)) return null;
        if (name === 'password_confirmation' && !el.hasAttribute('required')) {
            var pwd = el.form && el.form.querySelector('[name="password"]');
            if (pwd && isEmpty(pwd) && isEmpty(el)) return null;
        }

        var isNumberField = type === 'number' || el.getAttribute('inputmode') === 'numeric';

        // Empty number fields are coerced to 0 before validateForm — do not error on empty
        if (isEmpty(el)) {
            if (isNonTextControl(el) && !el.hasAttribute('required')) return null;
            if (isNumberField) return null;
            if (el.tagName === 'SELECT') return MSG.select;
            return MSG.required;
        }

        if (type === 'email' || name === 'email') {
            var emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/i;
            if (!emailRe.test(value)) return MSG.email;
        }

        if (isNumberField) {
            if (Number.isNaN(Number(value))) return MSG.number;

            // Default min is 0 (no negatives) unless data-allow-negative
            var allowNeg = el.dataset.allowNegative === '1';
            var minVal = (el.hasAttribute('min') && el.min !== '')
                ? Number(el.min)
                : (allowNeg ? null : 0);

            if (minVal !== null && !Number.isNaN(minVal) && Number(value) < minVal) {
                return MSG.min(minVal);
            }
            if (el.hasAttribute('max') && el.max !== '' && Number(value) > Number(el.max)) {
                return MSG.max(el.max);
            }
        }

        if (el.hasAttribute('minlength')) {
            var minL = parseInt(el.getAttribute('minlength'), 10);
            if (!Number.isNaN(minL) && value.length < minL) return MSG.minlength(minL);
        }

        if (el.hasAttribute('maxlength')) {
            var maxL = parseInt(el.getAttribute('maxlength'), 10);
            if (!Number.isNaN(maxL) && value.length > maxL) return MSG.maxlength(maxL);
        }

        if (name === 'phone' || type === 'tel' || el.dataset.validate === 'phone') {
            var phoneRe = /^[0-9+\-\s()]{6,20}$/;
            if (!phoneRe.test(value)) return MSG.phone;
        }

        if (el.hasAttribute('pattern')) {
            try {
                var re = new RegExp('^(?:' + el.getAttribute('pattern') + ')$');
                if (!re.test(value)) {
                    return el.getAttribute('data-pattern-message') || MSG.pattern;
                }
            } catch (e) { /* ignore bad pattern */ }
        }

        if (name === 'password_confirmation' || el.dataset.match) {
            var matchName = el.dataset.match || 'password';
            var matchEl = el.form && el.form.querySelector('[name="' + matchName + '"]');
            if (matchEl && String(matchEl.value) !== String(el.value)) {
                return MSG.passwordMatch;
            }
        }

        // Password min length when provided
        if (name === 'password' && value !== '' && value.length < 6 && !el.hasAttribute('minlength')) {
            return MSG.minlength(6);
        }

        return null;
    }

    function getFields(form) {
        return Array.prototype.slice.call(
            form.querySelectorAll('input, select, textarea')
        ).filter(function (el) {
            if (el.type === 'hidden') return false;
            if (el.name === '_token' || el.name === '_method') return false;
            if (el.disabled) return false;
            if (isSkippedReadonly(el)) return false;
            if (isRemarkOrOptional(el)) return false;
            return true;
        });
    }

    function coerceEmptyNumbers(form) {
        Array.prototype.forEach.call(
            form.querySelectorAll('input[type="number"], input[inputmode="numeric"]'),
            function (el) {
                if (el.disabled || isSkippedReadonly(el)) return;
                if (!el.name || el.name === '_token' || el.name === '_method') return;
                // Incomplete / cleared number → treat as 0
                if (el.validity && el.validity.badInput) {
                    el.value = '0';
                    return;
                }
                if (String(el.value == null ? '' : el.value).trim() === '') {
                    el.value = '0';
                }
            }
        );
    }

    function validateForm(form) {
        coerceEmptyNumbers(form);

        var fields = getFields(form);
        var firstInvalid = null;
        var ok = true;

        fields.forEach(function (el) {
            clearFieldError(el);
            var err = validateField(el);
            if (err) {
                ok = false;
                showFieldError(el, err);
                if (!firstInvalid) firstInvalid = el;
            }
        });

        if (firstInvalid) {
            // Keep red + error on the field we focus after failed submit
            form.dataset.derasSuppressClear = '1';
            try {
                firstInvalid.focus({ preventScroll: false });
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } catch (e) {
                try { firstInvalid.focus(); } catch (e2) { /* ignore */ }
            }
            delete form.dataset.derasSuppressClear;
        }

        return ok;
    }

    function onSubmitCapture(e) {
        var form = e.target;
        if (!(form instanceof HTMLFormElement)) return;
        if (!isValidateableForm(form)) return;
        // Allow already-confirmed DELETE submits through
        if (form.dataset.derasConfirmed === '1') return;

        try {
            coerceEmptyNumbers(form);

            if (!validateForm(form)) {
                e.preventDefault();
                e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') {
                    e.stopImmediatePropagation();
                }
            }
        } catch (err) {
            e.preventDefault();
            e.stopPropagation();
            if (typeof e.stopImmediatePropagation === 'function') {
                e.stopImmediatePropagation();
            }
            if (window.console && console.error) console.error('DERAS validation error:', err);
        }
    }

    function bindForm(form) {
        if (!isValidateableForm(form) || form.dataset.derasValidationBound === '1') return;
        form.dataset.derasValidationBound = '1';
        form.setAttribute('novalidate', 'novalidate');

        getFields(form).forEach(function (el) {
            var t = (el.getAttribute('type') || '').toLowerCase();
            var isNumberField = t === 'number' || el.getAttribute('inputmode') === 'numeric';

            // Enforce min=0 on editable number fields (block negatives)
            if (isNumberField && el.dataset.allowNegative !== '1' && !el.hasAttribute('min')) {
                el.setAttribute('min', '0');
            }

            if (isNumberField && el.dataset.allowNegative !== '1') {
                el.addEventListener('keydown', function (e) {
                    if (e.key === '-' || e.key === 'e' || e.key === 'E' || e.key === '+') {
                        e.preventDefault();
                    }
                });
                el.addEventListener('paste', function (e) {
                    var text = '';
                    try {
                        text = (e.clipboardData || window.clipboardData).getData('text') || '';
                    } catch (err) { return; }
                    if (/[-eE+]/.test(text) || (text !== '' && Number(text) < 0)) {
                        e.preventDefault();
                    }
                });
            }

            // Clear red/error as soon as the user focuses or edits (errors return only on submit)
            var clearOnInteract = function () {
                if (form.dataset.derasSuppressClear === '1') return;
                if (el.classList.contains('deras-field-invalid') || el.classList.contains('is-invalid')) {
                    clearFieldError(el);
                }
            };
            el.addEventListener('focus', clearOnInteract);
            el.addEventListener('input', clearOnInteract);
            el.addEventListener('change', clearOnInteract);
        });
    }

    function boot() {
        document.querySelectorAll('form').forEach(bindForm);
    }

    // Document-level capture so edit/update submits always hit validation
    document.addEventListener('submit', onSubmitCapture, true);

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
    window.addEventListener('load', boot);

    // Expose for manual use
    window.DerasValidation = {
        validateForm: validateForm,
        bindForm: bindForm,
        clearFieldError: clearFieldError,
        showFieldError: showFieldError
    };
})();
