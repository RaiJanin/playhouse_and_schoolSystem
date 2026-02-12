export class CustomCheckbox {
    constructor(buttonElement, checkBxIcon, checkBxInfo) {
        this.button = document.getElementById(`${buttonElement}`);
        this.icon = document.getElementById(`${checkBxIcon}`);
        this.info = document.getElementById(`${checkBxInfo}`);
        this.state = false;

        this.button.addEventListener('click', () => this.toggle());
    }

    toggle() {
        this.state = !this.state;

        this.icon.classList.toggle('fa-square-check', this.state);
        this.icon.classList.toggle('text-green-500', this.state);
        this.icon.classList.toggle('fa-square-xmark', !this.state);
        this.icon.classList.toggle('text-red-500', !this.state);

        this.button.dispatchEvent(
            new CustomEvent('change', {
                detail: { checked: this.state }
            })
        );

        return this.state;
    }

    setLabel(html) {
        this.info.innerHTML = html;
    }

    isChecked() {
        return this.state;
    }

    onChange(callback) {
        this.button.addEventListener('change', e => callback(e.detail.checked));
    }
}

