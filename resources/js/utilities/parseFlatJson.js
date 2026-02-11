/**
 * 
 * @param {Object} formDataObj - Flat Json to parse
 * @returns Parsed full json
 */
export function parseBracketedFormData(formDataObj) {
    const result = {};

    for (const key in formDataObj) {
        const value = formDataObj[key] === "" ? null : formDataObj[key];
        const keys = key.match(/[^\[\]]+/g);

        let temp = result;

        keys.forEach((k, i) => {
            const isLast = i === keys.length - 1;

            if (isLast) {
                temp[k] = value;
            } else {
                if (!temp[k]) temp[k] = /^\d+$/.test(keys[i + 1]) ? [] : {};
                temp = temp[k];
            }
        });
    }

    function convertArrays(obj) {
        if (typeof obj !== "object" || obj === null) return obj;
        const keys = Object.keys(obj);
        if (keys.every(k => /^\d+$/.test(k))) {
            return keys.map(k => convertArrays(obj[k]));
        } else {
            const newObj = {};
            for (const k in obj) newObj[k] = convertArrays(obj[k]);
            return newObj;
        }
    }

    return convertArrays(result);
}
