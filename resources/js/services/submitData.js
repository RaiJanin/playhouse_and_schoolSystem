/**
 * Submits JSON data to an API endpoint.
 *
 * @param {string} apiLink - The API endpoint URL.
 * @param {Object} dataObject - A JSON Object to be sent in the request body.
 * @returns Server response
 */

export async function submitData(apiLink, dataObject) {
    try {

        const response = await fetch(`${apiLink}`, {
            method: 'POST',
            headers: {
                'Content-type': 'application/json',
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