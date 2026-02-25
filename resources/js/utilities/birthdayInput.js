// Birthday input: numeric keypad, auto-format (MM / DD / YYYY), smart backspace, hidden ISO submit value
// Usage: add attribute `data-birthday` to the visible input (keep the input `id`, remove `name` if present)
// The script will create a hidden input with the original `name` for form submission (ISO: YYYY-MM-DD).
// Alternative: use `data-birthday-dropdown` for inline dropdown selects (month/day/year)

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

    // --- Helpers shared by keyboard + dropdown picker ---
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

    // --- Keyboard / direct typing wiring ---
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

  // --- Dropdown month / day / year picker (iPad-friendly) ---
  // Wrap input in a relatively positioned container so we can place a button on the right
  if (!input.closest('.birthday-input-wrapper')) {
      const wrapper = document.createElement('div');
      wrapper.className = 'birthday-input-wrapper';
      
      input.parentNode.insertBefore(wrapper, input);
      wrapper.appendChild(input);
  }

  const wrapperEl = input.closest('.birthday-input-wrapper');

  // Avoid adding multiple buttons if attachBirthdayInput is called again
  let dropdownBtn = wrapperEl.querySelector('.birthday-dropdown-button');
  if (!dropdownBtn) {
      dropdownBtn = document.createElement('button');
      dropdownBtn.type = 'button';
      dropdownBtn.className = 'birthday-dropdown-button';
      dropdownBtn.setAttribute('aria-label', 'Choose birthday from picker');
      dropdownBtn.setAttribute('title', 'Open birthday picker');
      dropdownBtn.innerHTML = '<i class="fa-solid fa-calendar-day"></i>';
      wrapperEl.appendChild(dropdownBtn);
  }

  const openDropdownPicker = () => {
      // If a picker is already open for this input, do nothing
      if (document.querySelector('.birthday-picker-overlay[data-for="' + (input.id || '') + '"]')) return;

      const existingRaw = digitsOnly(input.value).slice(0, 8);
      let mm = existingRaw.slice(0, 2) || '';
      let dd = existingRaw.slice(2, 4) || '';
      let yyyy = existingRaw.slice(4, 8) || '';

      // Fall back to today's date when there is nothing yet
      if (!mm || !dd || !yyyy) {
          const now = new Date();
          mm = String(now.getMonth() + 1).padStart(2, '0');
          dd = String(now.getDate()).padStart(2, '0');
          yyyy = String(now.getFullYear());
      }

      const overlay = document.createElement('div');
      overlay.className = 'birthday-picker-overlay fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4';
      overlay.dataset.for = input.id || '';

      const panel = document.createElement('div');
      panel.className = 'max-w-md w-full bg-white rounded-2xl shadow-xl p-4 space-y-4 animate-fadeIn';

      panel.innerHTML = `
        <div class="text-center space-y-1">
          <h2 class="text-lg font-semibold text-gray-800">Select Birthday</h2>
          <p class="text-xs text-gray-500">Month • Day • Year</p>
        </div>
        <div class="grid grid-cols-3 gap-3 mt-2">
          <div class="flex flex-col gap-1">
            <label class="text-[11px] font-semibold text-gray-600 tracking-wide uppercase">Month</label>
            <select class="birthday-picker-month bg-teal-50 border border-teal-400 rounded-lg px-2 py-2 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-400">
            </select>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-[11px] font-semibold text-gray-600 tracking-wide uppercase">Day</label>
            <select class="birthday-picker-day bg-teal-50 border border-teal-400 rounded-lg px-2 py-2 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-400">
            </select>
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-[11px] font-semibold text-gray-600 tracking-wide uppercase">Year</label>
            <select class="birthday-picker-year bg-teal-50 border border-teal-400 rounded-lg px-2 py-2 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-400">
            </select>
          </div>
        </div>
        <div class="flex justify-end gap-2 pt-2">
          <button type="button" class="birthday-picker-cancel text-xs sm:text-sm font-semibold px-3 sm:px-4 py-2 rounded-full border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">
            Cancel
          </button>
          <button type="button" class="birthday-picker-apply text-xs sm:text-sm font-semibold px-4 sm:px-5 py-2 rounded-full bg-teal-500 text-white shadow-md hover:bg-teal-600">
            Apply
          </button>
        </div>
      `;

      overlay.appendChild(panel);
      document.body.appendChild(overlay);

      const monthSelect = panel.querySelector('.birthday-picker-month');
      const daySelect = panel.querySelector('.birthday-picker-day');
      const yearSelect = panel.querySelector('.birthday-picker-year');
      const cancelBtn = panel.querySelector('.birthday-picker-cancel');
      const applyBtn = panel.querySelector('.birthday-picker-apply');

      const monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
      monthSelect.innerHTML = monthNames.map((name, idx) => {
          const val = String(idx + 1).padStart(2, '0');
          const selected = (val === mm) ? ' selected' : '';
          return `<option value="${val}"${selected}>${val} - ${name}</option>`;
      }).join('');

      const buildDays = () => {
          const maxDays = 31;
          const currentDay = daySelect.value || dd;
          let options = '';
          for (let d = 1; d <= maxDays; d++) {
              const v = String(d).padStart(2, '0');
              const selected = (v === currentDay) ? ' selected' : '';
              options += `<option value="${v}"${selected}>${v}</option>`;
          }
          daySelect.innerHTML = options;
      };

      const startYear = 1900;
      const endYear = 3000;
      let yearOptions = '';
      for (let y = endYear; y >= startYear; y--) {
          const selected = (String(y) === yyyy) ? ' selected' : '';
          yearOptions += `<option value="${y}"${selected}>${y}</option>`;
      }
      yearSelect.innerHTML = yearOptions;

      buildDays();

      const closeOverlay = () => {
          overlay.classList.add('animate-fadeOut');
          setTimeout(() => {
              if (overlay.parentNode) overlay.parentNode.removeChild(overlay);
          }, 200);
      };

      overlay.addEventListener('click', (evt) => {
          if (evt.target === overlay) {
              closeOverlay();
          }
      });

      cancelBtn.addEventListener('click', () => {
          closeOverlay();
      });

      applyBtn.addEventListener('click', () => {
          const selectedMM = monthSelect.value;
          const selectedDD = daySelect.value;
          const selectedYYYY = yearSelect.value;

          const raw = (selectedMM + selectedDD + selectedYYYY).slice(0, 8);
          setDisplayFromRaw(raw, raw.length);
          setValidityState(input, raw);
          input.focus();
          closeOverlay();
      });

      // Escape key support
      const onKeyDownGlobal = (e) => {
          if (e.key === 'Escape') {
              e.preventDefault();
              closeOverlay();
              document.removeEventListener('keydown', onKeyDownGlobal);
          }
      };
      document.addEventListener('keydown', onKeyDownGlobal);
  };

  dropdownBtn.addEventListener('click', (e) => {
      e.preventDefault();
      openDropdownPicker();
  });

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

// ============ Birthday Dropdown (Inline Selects) ============
// Creates three inline dropdowns for Month, Day, Year
// Usage: add attribute `data-birthday-dropdown` to a container element
// The container should have a `data-name` attribute for the hidden input name
// Example: <div id="parentBirthday" data-birthday-dropdown data-name="parentBirthday" required></div>

export function attachBirthdayDropdown(container) {
    if (!container || container.dataset.birthdayDropdownAttached) return;

    const originalName = container.getAttribute('data-name') || 'birthday';
    const isRequired = container.hasAttribute('required');
    const existingValue = container.dataset.birthdayValue || '';

    // Parse existing value (ISO format YYYY-MM-DD)
    let currentMM = '', currentDD = '', currentYYYY = '';
    if (existingValue && /^\d{4}-\d{2}-\d{2}$/.test(existingValue)) {
        currentYYYY = existingValue.slice(0, 4);
        currentMM = existingValue.slice(5, 7);
        currentDD = existingValue.slice(8, 10);
    }

    // Create hidden input for form submission
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = originalName;
    hiddenInput.value = existingValue;
    container.appendChild(hiddenInput);

    // Create wrapper for dropdowns
    const dropdownWrapper = document.createElement('div');
    dropdownWrapper.className = 'birthday-dropdown-wrapper flex gap-2';

    // Month select
    const monthSelect = document.createElement('select');
    monthSelect.className = 'birthday-month-select flex-1 bg-teal-100 border-2 border-teal-500 rounded-lg px-2 py-2 text-sm font-semibold focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 cursor-pointer';
    monthSelect.setAttribute('aria-label', 'Month');
    
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    let monthOptions = '<option value="">Month</option>';
    monthNames.forEach((name, idx) => {
        const val = String(idx + 1).padStart(2, '0');
        const selected = (val === currentMM) ? ' selected' : '';
        monthOptions += `<option value="${val}"${selected}>${name}</option>`;
    });
    monthSelect.innerHTML = monthOptions;

    // Day select
    const daySelect = document.createElement('select');
    daySelect.className = 'birthday-day-select flex-1 bg-teal-100 border-2 border-teal-500 rounded-lg px-2 py-2 text-sm font-semibold focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 cursor-pointer';
    daySelect.setAttribute('aria-label', 'Day');
    
    let dayOptions = '<option value="">Day</option>';
    for (let d = 1; d <= 31; d++) {
        const val = String(d).padStart(2, '0');
        const selected = (val === currentDD) ? ' selected' : '';
        dayOptions += `<option value="${val}"${selected}>${d}</option>`;
    }
    daySelect.innerHTML = dayOptions;

    // Year select
    const yearSelect = document.createElement('select');
    yearSelect.className = 'birthday-year-select flex-1 bg-teal-100 border-2 border-teal-500 rounded-lg px-2 py-2 text-sm font-semibold focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200 cursor-pointer';
    yearSelect.setAttribute('aria-label', 'Year');
    
    const currentYear = new Date().getFullYear();
    const startYear = currentYear - 100;
    let yearOptions = '<option value="">Year</option>';
    for (let y = currentYear; y >= startYear; y--) {
        const selected = (String(y) === currentYYYY) ? ' selected' : '';
        yearOptions += `<option value="${y}"${selected}>${y}</option>`;
    }
    yearSelect.innerHTML = yearOptions;

    dropdownWrapper.appendChild(monthSelect);
    dropdownWrapper.appendChild(daySelect);
    dropdownWrapper.appendChild(yearSelect);
    container.appendChild(dropdownWrapper);

    // Function to update hidden input and validation
    const updateValue = () => {
        const mm = monthSelect.value;
        const dd = daySelect.value;
        const yyyy = yearSelect.value;

        if (mm && dd && yyyy) {
            const isoDate = `${yyyy}-${mm}-${dd}`;
            hiddenInput.value = isoDate;
            container.dataset.birthdayValue = isoDate;
            
            // Validate date
            const daysInMonth = new Date(parseInt(yyyy), parseInt(mm), 0).getDate();
            if (parseInt(dd) > daysInMonth) {
                daySelect.setCustomValidity('Invalid day for selected month');
                container.classList.add('birthday-invalid');
                container.classList.remove('birthday-valid');
                container.removeAttribute('data-birthday-valid');
            } else {
                daySelect.setCustomValidity('');
                container.classList.remove('birthday-invalid');
                container.classList.add('birthday-valid');
                container.setAttribute('data-birthday-valid', 'true');
            }
        } else {
            hiddenInput.value = '';
            container.dataset.birthdayValue = '';
            
            if (isRequired && (mm || dd || yyyy)) {
                container.classList.add('birthday-invalid');
                container.classList.remove('birthday-valid');
                container.removeAttribute('data-birthday-valid');
            } else {
                container.classList.remove('birthday-invalid');
                container.classList.remove('birthday-valid');
                container.removeAttribute('data-birthday-valid');
            }
        }
    };

    // Add event listeners
    monthSelect.addEventListener('change', updateValue);
    daySelect.addEventListener('change', updateValue);
    yearSelect.addEventListener('change', updateValue);

    // Initial validation
    if (currentMM && currentDD && currentYYYY) {
        updateValue();
    }

    container.dataset.birthdayDropdownAttached = '1';
}

function initBirthdayDropdowns(scope = document) {
    const containers = scope.querySelectorAll('[data-birthday-dropdown]');
    containers.forEach(attachBirthdayDropdown);
}

export function requestBirthdayDropdownValidation(scope = document) {
    const containers = scope.querySelectorAll('[data-birthday-dropdown]');
    containers.forEach((container) => {
        const monthSelect = container.querySelector('.birthday-month-select');
        const daySelect = container.querySelector('.birthday-day-select');
        const yearSelect = container.querySelector('.birthday-year-select');
        
        if (monthSelect) monthSelect.dispatchEvent(new Event('change', { bubbles: true }));
    });
}

// Initialize birthday dropdowns on DOM ready
if (typeof document !== 'undefined') {
    document.addEventListener('DOMContentLoaded', () => {
        initBirthdayInputs();
        initBirthdayDropdowns();
    });
}
