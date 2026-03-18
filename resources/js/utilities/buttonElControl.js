/**
 * Disables a button and re-enables it after a countdown.
 * @param {HTMLButtonElement} btn - The button to disable.
 * @param {string} text - The text to restore after countdown.
 * @param {number} secs - Countdown in seconds (default 30s).
 */
export function makeButtonReEnableCountdown(btn, text, secs = 30) {
    let countdown = secs;
    btn.textContent = `${text} (${countdown}s)`;

    const timer = setInterval(() => {
        countdown--;
        btn.textContent = `${text} (${countdown}s)`;

        if(countdown <= 0) {
            clearInterval(timer);
            btn.textContent = `${text}`;
            btn.disabled = false;
        }
    }, 1000);
}