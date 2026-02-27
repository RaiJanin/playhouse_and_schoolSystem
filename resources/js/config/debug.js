const debugMode = false; //change this to view console.logs
const allowOverride = true;




/**
 * 
 * @param {'error'|'log'} options - Console Options
 * @param {*} message - Logs Title
 * @param {*} logs - Logs
 */
export function showConsole(options, message, logs = null, override = null) {
    if (debugMode || (override && allowOverride)) {
        
        const stack = new Error().stack || '';
        const callerLineRaw = stack.split('\n')[2]?.trim() || 'unknown location';
        let location = 'unknown';
        const match = callerLineRaw.match(/\(?([^\s()]+):(\d+):(\d+)\)?$/);
        if (match) {
            const filePath = match[1].replace(/^.*\/resources\/js\//, '');
            location = `${filePath}:_line_-${match[2]}:${match[3]}`;
        }

        const locationStyle = 'color: skyblue; font-weight: bold;';
        const messageStyle = 'color: white; font-weight: normal;';

        switch (options) {
            case 'log':
                if (logs !== null) {
                    console.log(`%c[${location}]\n%c${message}`, locationStyle, messageStyle, logs);
                } else {
                    console.log(`%c[${location}]\n%c${message}`, locationStyle, messageStyle);
                }
                break;

            case 'error':
                if (logs !== null) {
                    console.error(`%c[${location}]\n%c${message}`, locationStyle, 'color:red;font-weight:bold;', logs);
                } else {
                    console.error(`%c[${location}]\n%c${message}`, locationStyle, 'color:red;font-weight:bold;');
                }
                break;

            default:
                console.log(`%c[${location}] %cPlease select a valid option`, locationStyle, messageStyle);
        }
    }
}