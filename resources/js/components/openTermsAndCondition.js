export function openTermsandcondtion() {
    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'terms-and-conditions' }));
}