import '../config/global.js';
/**
 * 
 * @param {string} message - Message string
 * @param {'success'|'caution'|'error'} type - Message type
 * @param {number} duration - Notification duration. Default - 4000
 */
App.component.showAlert = function(message, type, duration = 4000) {
    const container = document.getElementById('alert-container');
    const notificationId = `notification-${Date.now()}`;

    const notification = document.createElement('div');
    notification.id = notificationId;
    notification.className = `bg-gray-50 border border-gray-200 text-black rounded-lg shadow-md overflow-hidden animate-slideIn relative transition-all duration-300`;
    notification.setAttribute('role', 'alert');
    notification.setAttribute('aria-live', 'assertive');

    let typeIcon = null;
    switch(type) {
        case 'success':
            typeIcon = `
                <i class="fa-solid fa-square-check px-2 text-lg text-green-500"></i>
            `;
            break;
        case 'caution':
            typeIcon = `
                <i class="fa-solid fa-circle-exclamation px-2 text-lg text-orange-400"></i>
            `;
            break;
        case 'error':
            typeIcon = `
                <i class="fa-solid fa-circle-exclamation px-2 text-lg text-red-600"></i>
            `;
            break;
        default:
            typeIcon = '';
            break;
    }

    notification.innerHTML = `
        <div class="flex items-start p-4">
            
            <div class="flex-1">
                <p class="sm:text-sm text-xs font-medium">${typeIcon} ${message}</p>
            </div>
            <button onclick="App.component.dismissAlert('${notificationId}')" class="ml-4 text-black hover:text-gray-200 focus:outline-none" aria-label="Close notification">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="absolute bottom-0 left-0 h-1 bg-gray-300">
            <div class="progress-bar-fill h-1 w-full bg-gray-800"></div>
        </div>
    `;

    container.prepend(notification);

    const timeout = setTimeout(() => {
        App.component.dismissAlert(notificationId);
    }, duration);

    notification.timeout = timeout;
}

/**
 * Dismisses and removes a notification element with an exit animation.
 *
 * @function App.component.dismissAlert
 * @memberof App.component
 * @param {string} id - The ID of the notification element to dismiss.
 * @returns {void}
 */
App.component.dismissAlert = function (id) {
    const notification = document.getElementById(id);
    if (notification) {
        clearTimeout(notification.timeout);
        notification.classList.remove('animate-slideIn');
        notification.classList.add('animate-slideOut');

        notification.addEventListener('animationend', () => {
            notification.remove();
        }, { once: true });
    }
}

/**
 * Displays a critical error alert modal with the provided message and attaches a close handler.
 *
 * @function App.component.criticalAlert
 * @memberof App.component
 * @param {string} err - The error message to display in the alert modal.
 * @returns {void}
 */
App.component.criticalAlert = function (err) {
    const criticalAlertMesssageContainer = document.getElementById('alert-modal');
    criticalAlertMesssageContainer.classList.remove('animate-fadeOut');
    criticalAlertMesssageContainer.classList.add('animate-fadeIn');
    criticalAlertMesssageContainer.hidden = false;
    criticalAlertMesssageContainer.querySelector('.error-msg').textContent = err;

    criticalAlertMesssageContainer.querySelector('.close-err-msg').addEventListener('click', () => { closeCriticalError(criticalAlertMesssageContainer) });

    setTimeout(() => {
        closeCriticalError(criticalAlertMesssageContainer);
    }, 10000);
}

function closeCriticalError(scope) {
    if(scope.hidden) return;
    scope.classList.remove('animate-fadeIn');
    scope.classList.add('animate-fadeOut');

    setTimeout(() => {
        scope.hidden = true;
    }, 300);
};