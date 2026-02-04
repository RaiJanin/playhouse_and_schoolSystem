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