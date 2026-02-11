const checkBxIcon = document.getElementById('check-icon');

let checkState = false;

export const checkBxInfo = document.getElementById('check-info');
export const checkBxBtn = document.getElementById('custom-checkbox');

export function customCheckBx() {
    checkState = !checkState;
    
    if(checkState) {
        checkBxIcon.classList.remove('fa-square-xmark', 'text-red-500');
        checkBxIcon.classList.add('fa-square-check', 'text-green-500');
        return false;
    } else {
        checkBxIcon.classList.remove('fa-square-check', 'text-green-500');
        checkBxIcon.classList.add('fa-square-xmark', 'text-red-500');
        return true;
    }
}
