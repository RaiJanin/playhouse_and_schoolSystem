/**
 * Submits JSON data to an API endpoint.
 *
 * @param {string} apiLink - The API endpoint URL.
 * @param {Object} dataObject - A JSON Object to be sent in the request body.
 * @param {'POST'|'PATCH'|'PUT'} method - Request method (default = POST)
 * @param {string} routeParam - Route parameter
 * @returns Server response
 */

export async function submitData(apiLink, dataObject, method = 'POST', routeParam = null) {
    try {
        
        const reqURL = method !== 'POST' ? `${apiLink}/${routeParam}` : apiLink;

        const response = await fetch(reqURL, {
            method: method.toUpperCase(),
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(dataObject)
        });

        return await response.json();

    } catch (error) {
        throw error;
    }
    
}

/**
 * Sends GET or DELETE requests
 * 
 * @param {string} apiLink - The API endpoint URL
 * @param {Array} routeParam - Route parameter
 * @param {'GET'|'DELETE'} method - Request Method
 * @returns Server response
 */
export async function getOrDelete(method, apiLink, routeParam = []) {
    try {
        const reqURl = routeParam.length === 0 ? apiLink : `${apiLink}/${routeParam.join('/')}`;
        const options = {
            method: method.toUpperCase(),
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        }

        if(method === 'DELETE') {
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            options.headers['X-CSRF-TOKEN'] = csrf;
        }
        
        const response = await fetch(reqURl, options);
        return await response.json();

    } catch (error) {
        throw error;
    }
}