export class CustomCheckbox {
    constructor(buttonElement) {
        this.button = buttonElement;
        this.icon = this.button.querySelector('.check-icon');
        this.info = this.button.querySelector('.check-info');
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
}

