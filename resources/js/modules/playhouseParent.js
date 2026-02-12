import { CustomCheckbox } from '../components/customCheckbox.js';

document.querySelectorAll('.custom-checkbox').forEach(button => {
    const guardianCheckBx = new CustomCheckbox(button);

    guardianCheckBx.setLabel(`Add Guardian `);

    button.addEventListener('change', (event) => {
        document.getElementById('guardian-form').hidden = !event.detail.checked;
    });
});
