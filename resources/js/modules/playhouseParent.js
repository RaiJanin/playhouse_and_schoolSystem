import {
    customCheckBx,
    checkBxBtn,
    checkBxInfo
} from '../components/customCheckbox.js';

document.addEventListener('DOMContentLoaded', () => {
    checkBxInfo.innerHTML += `Guardian:`;
        checkBxBtn.addEventListener('click', () => {
            customCheckBx();
        });
})
