export let selectedChildState = {
    selectCount: 0
};

export function attachFields(data, index) {
    const hasPhoto = data.photo && data.photo.length > 0;
    const photoHtml = hasPhoto 
        ? `<img src="/${data.photo}" alt="${data.firstname}'s photo" class="w-24 h-24 rounded-full object-cover border-2 border-teal-500 mx-auto mb-2">`
        : `<div class="w-24 h-24 rounded-full bg-gray-200 border-2 border-gray-300 mx-auto mb-2 flex items-center justify-center">
            <i class="fa-solid fa-user text-3xl text-gray-400"></i>
          </div>`;
    
    return `
        <div class="attached-fields child-entry flex flex-col">
            <input type="name" name="child[${index}][name]" value="${data.firstname}" hidden required/>
            <input type="hidden" name="child[${index}][birthday]" value="${data.birthday}"/>
            <input type="hidden" name="child[${index}][photo]" value="${data.photo}"/>
            
            <!-- Photo Display for Returning Users -->
            <div class="text-center mb-3">
                ${photoHtml}
                <p class="text-sm font-semibold text-gray-700">${data.firstname}</p>
            </div>
            
            <div class="h-full">
                <label class="block text-base font-semibold text-gray-900 mb-2">Playtime Duration <span class="text-red-600">*</span></label>
                <div class="relative">
                    <select name="child[${index}][playDuration]" class="child-duration bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300 cursor-pointer appearance-none" required>
                        <option value="1">1 Hour = ₱100</option>  
                        <option value="2">2 Hours = ₱200</option> 
                        <option value="3">3 Hours = ₱300</option>
                        <option value="4">4 Hours = ₱400</option>
                        <option value="unlimited">Unlimited = ₱500</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-teal-500">
                        <i class="fa-solid fa-chevron-down text-sm"></i>
                    </div>
                </div>
            </div>
            <div class="h-full">
                <label class="block text-base font-semibold text-gray-900 mb-2">Add Socks</label>
                <div class="relative">
                    <select name="child[${index}][addSocks]" data-child-index="${index}" class="edit-child-socks bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300 cursor-pointer appearance-none" required>
                        <option value="0">No</option>  
                        <option value="1">Yes</option> 
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-teal-500">
                        <i class="fa-solid fa-chevron-down text-sm"></i>
                    </div>
                </div>
            </div>
        </div>
    `;
}

export function validateSelectedChild(removePrompt = false) {
    let validated = true;
    const addChildPrompt = document.getElementById('add-child-prompt');

    if(selectedChildState.selectCount === 0 && !removePrompt) {
        addChildPrompt.hidden = false;
        addChildPrompt.textContent = 'You need to add at least one child to continue.';
        validated = false;
    } else {
        addChildPrompt.textContent = '';
        addChildPrompt.hidden = true;
    }

    return validated;
}