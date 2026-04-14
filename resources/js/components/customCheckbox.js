/**
 * CustomCheckbox class for managing checkbox-like toggle functionality
 * @class
 */
export class CustomCheckbox {
    /**
     * Creates an instance of CustomCheckbox
     * @constructor
     * @param {string} buttonElement - ID of the button element
     * @param {string} checkBxIcon - ID of the icon element
     * @param {string} checkBxInfo - ID of the info element
     * @param {string} primaryCheckboxColor - Primary coor of the checkbox element
     */
    constructor(buttonElement, checkBxIcon, checkBxInfo, primaryCheckboxColor = null) {
        this.button = document.getElementById(`${buttonElement}`);
        this.icon = document.getElementById(`${checkBxIcon}`);
        this.info = document.getElementById(`${checkBxInfo}`);
        this.state = false;
        this.pColor = primaryCheckboxColor
            ? primaryCheckboxColor
            : "text-gray-500";

        this.button.addEventListener("click", () => this.toggle());
    }

    /**
     * Toggles the checkbox state
     * @method toggle
     * @returns {boolean} The new state after toggling
     */
    toggle() {
        this.state = !this.state;

        this.icon.classList.toggle("fa-solid", this.state);
        this.icon.classList.toggle("fa-square-check", this.state);
        this.icon.classList.toggle("text-green-500", this.state);
        this.icon.classList.toggle("fa-regular", !this.state);
        this.icon.classList.toggle("fa-square", !this.state);
        this.icon.classList.toggle(this.pColor, !this.state);

        this.button.dispatchEvent(
            new CustomEvent("change", {
                detail: { checked: this.state },
            }),
        );

        return this.state;
    }

    /**
     * Sets the label content
     * @method setLabel
     * @param {string} html - HTML content to set as label
     * @returns {void}
     */
    setLabel(html) {
        this.info.innerHTML = html;
    }

    /**
     * Gets the current checked state
     * @method isChecked
     * @returns {boolean} Current checked state
     */
    isChecked() {
        return this.state;
    }

    /**
     * Registers a callback for change events
     * @method onChange
     * @param {function} callback - Callback function receiving the checked state
     * @returns {void}
     */
    onChange(callback) {
        this.button.addEventListener("change", (e) =>
            callback(e.detail.checked),
        );
    }
}

