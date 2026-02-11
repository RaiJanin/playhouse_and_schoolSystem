import {
    customCheckBx,
    checkBxBtn,
    checkBxInfo
} from '../components/customCheckbox.js';

import { readStep } from '../utilities/stepState.js';

document.addEventListener('DOMContentLoaded', () => {

    if(readStep() == 'parent') {
        checkBxInfo.innerHTML += `Guardian:`;
        checkBxBtn.addEventListener('click', () => {
            customCheckBx();
        });
    }
});
