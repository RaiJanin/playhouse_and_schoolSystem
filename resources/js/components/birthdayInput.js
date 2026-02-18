// Birthday input: numeric keypad, auto-format (MM / DD / YYYY), smart backspace, hidden ISO submit value
// Usage: add attribute `data-birthday` to the visible input (keep the input `id`, remove `name` if present)
// The script will create a hidden input with the original `name` for form submission (ISO: YYYY-MM-DD).

const SEPARATOR = ' / ';

function digitsOnly(str) {
    return (str || '').replace(/\D/g, '');
}

function formatDisplay(rawDigits) {
    const r = rawDigits.slice(0, 8);

    if (r.length <= 2) return r;
    if (r.length <= 4) return r.slice(0, 2) + SEPARATOR + r.slice(2);

    return r.slice(0, 2) + SEPARATOR + r.slice(2, 4) + SEPARATOR + r.slice(4);
}

function findCursorPosByDigitCount(display, digitCount) {

    for (let i = 0; i <= display.length; i++) {
      if (display.slice(0, i).replace(/\D/g, '').length === digitCount) return i;
    }

    return display.length;
}

function isValidDateParts(mm, dd, yyyy) {
    if (!mm || !dd || !yyyy) return false;

    const m = parseInt(mm, 10);
    const d = parseInt(dd, 10);
    const y = parseInt(yyyy, 10);

    if (isNaN(m) || isNaN(d) || isNaN(y)) return false;
    if (m < 1 || m > 12) return false;

    const daysInMonth = new Date(y, m, 0).getDate();
    if (d < 1 || d > daysInMonth) return false;

    if (y < 1900 || y > 3000) return false;

    return true;
}

function rawToISO(raw) {
    if (raw.length !== 8) return '';

    const mm = raw.slice(0, 2);
    const dd = raw.slice(2, 4);
    const yyyy = raw.slice(4, 8);

    if (!isValidDateParts(mm, dd, yyyy)) return '';

    return `${yyyy}-${mm.padStart(2, '0')}-${dd.padStart(2, '0')}`;
}

export function attachBirthdayInput(input) {
    if (!input || input.dataset.birthdayAttached) return;

    
    const originalName = input.getAttribute('name');
    let hidden = null;

    const next = input.nextElementSibling;

    if (next && next.tagName === 'INPUT' && next.type === 'hidden' && next.name) {
        hidden = next;
    } else {
        const prev = input.previousElementSibling;

        if (prev && prev.tagName === 'INPUT' && prev.type === 'hidden' && prev.name) {
          hidden = prev;
        }
    }

    if (!hidden && originalName) {
      input.removeAttribute('name');
      hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = originalName;
      hidden.id = (input.id ? input.id + '-iso' : 'birthday-hidden-' + Math.random().toString(36).slice(2));
      input.insertAdjacentElement('afterend', hidden);
    } else if (hidden && originalName) {
      input.removeAttribute('name');
    }

    input.setAttribute('inputmode', 'numeric');
    input.setAttribute('autocomplete', 'bday');

    input.type = 'tel';
    input.placeholder = 'MM / DD / YYYY';
    input.classList.add('birthday-input');
    input.setAttribute('aria-label', 'Birthday, format: month, day, year');

    const setDisplayFromRaw = (raw, caretDigitsBefore = null) => {
        const formatted = formatDisplay(raw);
        input.value = formatted;

        if (hidden) hidden.value = rawToISO(raw);

        if (caretDigitsBefore !== null) {
            const newPos = findCursorPosByDigitCount(formatted, caretDigitsBefore);
            input.setSelectionRange(newPos, newPos);
        }
    };

    const setValidityState = (el, raw) => {
        const isRequired = el.required;

        el.setCustomValidity('');

        const addInvalidBorder = () => {
            el.classList.add('border-red-600');
            el.classList.remove('border-teal-500');
        };
        const removeInvalidBorder = () => {
            el.classList.remove('border-red-600');
        };
        const addValidBorder = () => {
            el.classList.remove('border-red-600');
            el.classList.add('border-teal-500');
        };

        const showValidationUI = el.dataset.validationTriggered === 'true';
        const isFocused = (document.activeElement === el);

        if (isFocused) {
            removeInvalidBorder();
            el.classList.add('border-teal-500');
        }

        if (raw.length === 0) {

            if (isRequired) {
                el.setCustomValidity('Please enter a birthdate');
                el.classList.remove('birthday-valid');
                el.classList.add('birthday-invalid');
                el.removeAttribute('data-birthday-valid');

                if (showValidationUI && !isFocused) addInvalidBorder(); else addValidBorder();

                return false;
            } else {
                el.classList.remove('birthday-valid');
                el.classList.remove('birthday-invalid');
                el.removeAttribute('data-birthday-valid');

                removeInvalidBorder();

                el.classList.add('border-teal-500');

                return true;
            }
        }

        if (raw.length < 8) {
            if (isRequired) {
                el.setCustomValidity('Please complete the date (MM / DD / YYYY)');
            } else {
                el.setCustomValidity('');
            }

            el.classList.remove('birthday-valid');
            el.classList.add('birthday-invalid');
            el.removeAttribute('data-birthday-valid');

            if (showValidationUI && !isFocused) addInvalidBorder(); else addValidBorder();

            return false;
        }

        const iso = rawToISO(raw);
        if (!iso) {
            el.setCustomValidity('Please enter a valid date');
            el.classList.remove('birthday-valid');
            el.classList.add('birthday-invalid');
            el.removeAttribute('data-birthday-valid');
          
            if (showValidationUI && !isFocused) addInvalidBorder(); else addValidBorder();
          
            return false;
        }

        el.setCustomValidity('');
        el.classList.remove('birthday-invalid');
        el.classList.add('birthday-valid');
        el.setAttribute('data-birthday-valid', 'true');

        removeInvalidBorder();
        addValidBorder();
        
        return true;
    };

    const onInput = (e) => {
        const el = e.target;
        const prevCursor = el.selectionStart || 0;
        const digitsBeforeCursor = digitsOnly(el.value.slice(0, prevCursor)).length;

        const raw = digitsOnly(el.value).slice(0, 8);
        setDisplayFromRaw(raw, digitsBeforeCursor);

        setValidityState(el, raw);
    };

    const onKeyDown = (e) => {
        const el = e.target;

        if (e.key === 'Backspace') {
          const pos = el.selectionStart;
          const before = el.value.slice(0, pos);
          const lastChar = before.slice(-1);

          if (lastChar === '/' || lastChar === ' ') {
            e.preventDefault();
          
            const digitsBefore = digitsOnly(before).length;
            
            let raw = digitsOnly(el.value).slice(0, 8);
            raw = raw.slice(0, Math.max(0, digitsBefore - 1)) + raw.slice(digitsBefore);
            setDisplayFromRaw(raw, Math.max(0, digitsBefore - 1));
          }
        }
  };

  const onPaste = (e) => {
      e.preventDefault();

      const text = (e.clipboardData || window.clipboardData).getData('text') || '';
      const raw = digitsOnly(text).slice(0, 8);

      setDisplayFromRaw(raw, raw.length);
      input.dispatchEvent(new Event('input', { bubbles: true }));
  };

  input.addEventListener('input', onInput);
  input.addEventListener('keydown', onKeyDown);
  input.addEventListener('paste', onPaste);

  input.addEventListener('focus', () => {
      input.classList.add('birthday-active');
      const d = digitsOnly(input.value);
      const pos = findCursorPosByDigitCount(input.value, d.length);
      input.setSelectionRange(pos, pos);
  });

  input.addEventListener('blur', () => {
      input.classList.remove('birthday-active');
      const raw = digitsOnly(input.value).slice(0, 8);
      setValidityState(input, raw);
  });

  let initialRaw = '';
  if (hidden && hidden.value) {
      const iso = hidden.value;
      const m = iso.slice(5, 7);
      const d = iso.slice(8, 10);
      const y = iso.slice(0, 4);

      if (/^\d{4}-\d{2}-\d{2}$/.test(iso)) initialRaw = (m + d + y).slice(0, 8);
  }

  if (!initialRaw) {
      initialRaw = digitsOnly(input.value).slice(0, 8);
  }

  setDisplayFromRaw(initialRaw, null);
  
  setValidityState(input, initialRaw);

  input.dataset.birthdayAttached = '1';
}

function initBirthdayInputs(scope = document) {
    const inputs = scope.querySelectorAll('input[data-birthday]');
    inputs.forEach(attachBirthdayInput);
}

export function requestBirthdayValidation(scope = document) {
    const inputs = scope.querySelectorAll('input[data-birthday]');
    inputs.forEach((input) => {
      input.dataset.validationTriggered = 'true';
      input.dispatchEvent(new Event('input', { bubbles: true }));
      input.dispatchEvent(new Event('blur', { bubbles: true }));
    });
}

if (typeof document !== 'undefined') {
    document.addEventListener('DOMContentLoaded', () => initBirthdayInputs());
}
