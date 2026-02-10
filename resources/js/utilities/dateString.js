/**
 * 
 * @param {'longDate'|'shortDate'|'iso'|'timeOnly24'|'timeOnly12'} options 
 *  Date type to use
 * - longDate = "January 6, 2025"
 * - shortDate = "Jan. 6, 2025"
 * - iso = "2025-01-06"
 * - timeOnly24 = "14:30"
 * - timeOnly12 = "2:30pm"
 * 
 * @param {string} dateString - Raw date string
 * @returns {string} - Formatted date string
 */

export function dateToString(options, dateString) {
    console.log(dateString);

    if(dateString == null || dateString == '') {
        return ' ';
    }

    const dateStamp = new Date(dateString);
    switch (options) {
        case 'longDate':
            return dateStamp.toLocaleDateString('en-US', {
                month: 'long',
                day: 'numeric',
                year: 'numeric',
            });

        case 'shortDate':
            return dateStamp.toLocaleDateString('en-Us', {
                month: 'short',
                day: 'numeric',
                year: 'numeric',
            });

        case 'iso':
            return dateStamp.toISOString().split('T')[0];

        case 'timeOnly24':
            return dateStamp.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit'
            });
        
        case 'timeOnly12':
            return dateStamp.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });

        default:
            return dateStamp.toLocaleDateString();
    }
}