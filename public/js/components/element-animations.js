export function animateContainerHeight(element, updateFn) {
    const startHeight = element.offsetHeight;
    updateFn();
    const endHeight = element.offsetHeight;

    element.style.height = startHeight + 'px';
    element.offsetHeight;
    element.style.transition = 'height 0.3s cubic-bezier(0.25, 1, 0.5, 1)';
    element.style.height = endHeight + 'px';

    element.addEventListener('transitionend', function cleanup() {
        element.style.height = '';
        element.style.transition = '';
        element.removeEventListener('transitionend', cleanup);
    });
}