const select = document.getElementById('howKnowUs');
const continueBtn = document.getElementById('continueCheckin');

if (select && continueBtn) {
    select.addEventListener('change', () => {
        continueBtn.disabled = !select.value;
    });

    continueBtn.addEventListener('click', () => {
        if (!select.value) return;

        const registrationUrl = continueBtn.dataset.registrationUrl;
        if (!registrationUrl) return;

        const url = new URL(registrationUrl, window.location.origin);
        url.searchParams.set('source', select.value);
        window.location.href = url.toString();
    });
}
