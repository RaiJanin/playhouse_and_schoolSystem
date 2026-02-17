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
  // return smallest index i such that display.slice(0, i).replace(/\D/g, '').length === digitCount
  let digitsSeen = 0;
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
  const currentYear = new Date().getFullYear();
  if (y < 1900 || y > currentYear) return false; // reasonable range
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

  // Attempt to find an existing hidden sibling (we added hidden inputs in the Blade templates).
  // If not found, and the visible input has a `name`, create a hidden ISO input and remove the visible `name`.
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
    // remove name from visible input to avoid duplicate submission
    input.removeAttribute('name');
    hidden = document.createElement('input');
    hidden.type = 'hidden';
    hidden.name = originalName;
    hidden.id = (input.id ? input.id + '-iso' : 'birthday-hidden-' + Math.random().toString(36).slice(2));
    input.insertAdjacentElement('afterend', hidden);
  } else if (hidden && originalName) {
    // ensure only the hidden field carries the name
    input.removeAttribute('name');
  }

  // set attributes to encourage numeric keypad on iPad/tablet
  input.setAttribute('inputmode', 'numeric');
  input.setAttribute('autocomplete', 'bday');
  // remove `pattern` because the visible value contains separators (slashes/spaces)
  input.type = 'tel'; // reliable numeric keypad on iOS
  input.placeholder = 'MM / DD / YYYY';
  input.classList.add('birthday-input');
  input.setAttribute('aria-label', 'Birthday, format: month, day, year');

  const setDisplayFromRaw = (raw, caretDigitsBefore = null) => {
    const formatted = formatDisplay(raw);
    input.value = formatted;

    // update hidden ISO value
    if (hidden) hidden.value = rawToISO(raw);

    // set caret
    if (caretDigitsBefore !== null) {
      const newPos = findCursorPosByDigitCount(formatted, caretDigitsBefore);
      input.setSelectionRange(newPos, newPos);
    }
  };

  const setValidityState = (el, raw) => {
    const isRequired = el.required;

    // clear by default
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

    // If focused, always show the focus/highlight border (do not show red)
    if (isFocused) {
      removeInvalidBorder();
      el.classList.add('border-teal-500');
    }

    if (raw.length === 0) {
      // empty
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
      // incomplete
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

    // raw.length === 8
    const iso = rawToISO(raw);
    if (!iso) {
      el.setCustomValidity('Please enter a valid date');
      el.classList.remove('birthday-valid');
      el.classList.add('birthday-invalid');
      el.removeAttribute('data-birthday-valid');
      if (showValidationUI && !isFocused) addInvalidBorder(); else addValidBorder();
      return false;
    }

    // valid
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

    // update validity state (this also toggles visual classes)
    setValidityState(el, raw);
  };

  const onKeyDown = (e) => {
    const el = e.target;
    if (e.key === 'Backspace') {
      // when caret is right after a separator, move it back one position so deletion removes a digit
      const pos = el.selectionStart;
      const before = el.value.slice(0, pos);
      const lastChar = before.slice(-1);
      if (lastChar === '/' || lastChar === ' ') {
        // prevent default so we can adjust digits/caret predictably
        e.preventDefault();
        // compute how many digits are before caret
        const digitsBefore = digitsOnly(before).length;
        // remove one digit from the raw representation
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
    // put caret at end if empty
    const d = digitsOnly(input.value);
    const pos = findCursorPosByDigitCount(input.value, d.length);
    input.setSelectionRange(pos, pos);
  });
  input.addEventListener('blur', () => {
    input.classList.remove('birthday-active');
    // run final validity check on blur — but only update the red error outline if validation was requested
    const raw = digitsOnly(input.value).slice(0, 8);
    setValidityState(input, raw);
  });

  // initialize display from existing value if any (accept ISO or digits)
  let initialRaw = '';
  if (hidden && hidden.value) {
    // if hidden ISO exists, format it
    const iso = hidden.value; // expect YYYY-MM-DD
    const m = iso.slice(5, 7);
    const d = iso.slice(8, 10);
    const y = iso.slice(0, 4);
    if (/^\d{4}-\d{2}-\d{2}$/.test(iso)) initialRaw = (m + d + y).slice(0, 8);
  }
  if (!initialRaw) {
    // maybe visible input has digits already
    initialRaw = digitsOnly(input.value).slice(0, 8);
  }
  setDisplayFromRaw(initialRaw, null);
  // initial validity state
  setValidityState(input, initialRaw);

  input.dataset.birthdayAttached = '1';
}

export function initBirthdayInputs(scope = document) {
  const inputs = scope.querySelectorAll('input[data-birthday]');
  inputs.forEach(attachBirthdayInput);
}

// called by the form stepper when user clicks Next — shows validation UI for birthday inputs
export function requestBirthdayValidation(scope = document) {
  const inputs = scope.querySelectorAll('input[data-birthday]');
  inputs.forEach((input) => {
    // mark that the form has requested validation display
    input.dataset.validationTriggered = 'true';
    // trigger update via input handler
    input.dispatchEvent(new Event('input', { bubbles: true }));
    input.dispatchEvent(new Event('blur', { bubbles: true }));
  });
}

// auto-init when module is imported
if (typeof document !== 'undefined') {
  document.addEventListener('DOMContentLoaded', () => initBirthdayInputs());
}
