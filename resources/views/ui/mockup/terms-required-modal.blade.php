<!-- Modal partial: shows when user filled phone + pressed Next but didn't accept terms -->
<div id="terms-required-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 px-4 py-6" aria-hidden="true">
  <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-6 text-center">
    <h3 class="text-xl font-bold text-gray-800 mb-2">Please accept the terms first</h3>
    <p class="text-sm text-gray-600 mb-4">You need to agree to the
      <span><a target="__blank" href="https://termly.io/html_document/website-terms-and-conditions-text-format/" class="text-blue-500 underline">terms and conditions.</a></span>
      Please click the checkbox before continuing.</p>

    <div class="flex justify-center gap-3">
      <button id="terms-required-view" class="px-4 py-2 rounded-md bg-teal-500 text-white font-semibold shadow hover:bg-teal-600">View terms</button>
      <button id="terms-required-ok" class="px-4 py-2 rounded-md bg-gray-100 text-gray-800 border border-gray-200 hover:bg-gray-50">Okay</button>
    </div>

    <button id="terms-required-close-x" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600" aria-label="close">✕</button>
  </div>
</div>

<script>
// Attach behavior without modifying any other files. This script will only run if expected elements exist.
(function () {
  function el(selector) { return document.querySelector(selector); }
  document.addEventListener('DOMContentLoaded', function () {
    const modalWrap = el('#terms-required-modal');
    const btnView = el('#terms-required-view');
    const btnOk = el('#terms-required-ok');
    const btnCloseX = el('#terms-required-close-x');

    // Selectors used by registration UI elsewhere in the app
    const phoneInput = el('#phone');
    const nextBtn = el('#next-btn');
    const agreeCheckbox = el('#agree') || document.querySelector('input[name="agree"]');

    if (!modalWrap || !nextBtn || !phoneInput || !agreeCheckbox) return; // nothing to do

    function openModal() {
      modalWrap.classList.remove('hidden');
      modalWrap.classList.add('flex');
      // make modal accessible
      modalWrap.setAttribute('aria-hidden', 'false');
      // focus OK so keyboard users can close
      btnOk && btnOk.focus();
    }

    function closeModal() {
      modalWrap.classList.remove('flex');
      modalWrap.classList.add('hidden');
      modalWrap.setAttribute('aria-hidden', 'true');
      // focus the agree checkbox to prompt the user
      agreeCheckbox.focus();
    }

    // Intercept the Next button click (capture phase to run before other handlers)
    nextBtn.addEventListener('click', function (ev) {
      const phoneFilled = phoneInput.value && phoneInput.value.trim().length > 0;
      const agreed = agreeCheckbox.checked;

      if (phoneFilled && !agreed) {
        // prevent advancing and show modal
        ev.stopImmediatePropagation && ev.stopImmediatePropagation();
        ev.preventDefault && ev.preventDefault();
        openModal();
      }
      // otherwise allow default handlers to run
    }, true);

    // Modal controls
    btnView && btnView.addEventListener('click', function () {
      window.open('https://termly.io/html_document/website-terms-and-conditions-text-format/', '__blank');
    });

    btnOk && btnOk.addEventListener('click', function () {
      closeModal();
    });
    btnCloseX && btnCloseX.addEventListener('click', closeModal);

    // close on ESC
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !modalWrap.classList.contains('hidden')) closeModal();
    });
  });
})();
</script>