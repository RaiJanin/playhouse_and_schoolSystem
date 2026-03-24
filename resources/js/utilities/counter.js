/**
 * Counts how many items in the given scope have a value of '1'.
 *
 * @function incrementOnlyOnesViaScope
 * @param {Array<Object>} scope - An array of objects to iterate over.
 * @param {*} scope[].value - The value property of each object (expected to be comparable to string '1').
 * @returns {number} The total count of items where value === '1'.
 *
 * @example
 * const data = [
 *   { value: '1' },
 *   { value: '0' },
 *   { value: '1' }
 * ];
 *
 * const result = incrementViaScope(data);
 * console.log(result); // 2
 */
export function incrementOnlyOnesViaScope(scope) {
    let count = 0;

    scope.forEach(val => {
        if(val && val.value === '1') count++;
    });

    return count;
}