/**
 * Renumbers and updates label text for a list of DOM items after deletion or reordering.
 *
 * @function cleanDeletedElement
 * @param {HTMLElement[]} items - Array of DOM elements to be updated.
 * @param {string} selector - CSS selector used to find the label element inside each item.
 * @param {string} textLabel - Base text used for numbering each item.
 * @param {string|null} [extra=null] - Optional extra HTML/text appended to each label.
 * @returns {number} The total number of processed items.
 */
export function cleanDeletedElement(items, selector, textLabel, extra = null) {
    items.forEach((item, index) => {
        const label = item.querySelector(`${selector}`);
        if (label) {
            label.textContent = `${textLabel} ${index +1}`;
            if (extra) {
                label.innerHTML += `${extra}`;
            }
        }
    });
    return items.length;
}