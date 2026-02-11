/**
 * Submits JSON data to an API endpoint.
 *
 * @param {string} apiLink - The API endpoint URL.
 * @param {Object} dataObject - A JSON Object to be sent in the request body.
 * 
 */

export function submitData(apiLink, dataObject) {
    console.log(dataObject);
    fetch(`${apiLink}`, {
        method: 'POST',
        headers: {
            'Content-type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(dataObject)
    }).
    then(async response => {
        const contentType = response.headers.get('content-type');

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Server Error Response:', errorText);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        if (contentType && contentType.includes('application/json')) {
            const reply = await response.json();
            console.log("Reply from backend:", reply);
            return reply;
        } else {
            const text = await response.text();
            console.error("Expected JSON but received:", text);
            throw new Error("Response is not JSON");
        }
    }).
    catch(err => {
        console.error(err);
    });
}