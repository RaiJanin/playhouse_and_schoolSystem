const container = document.getElementById("items-container");
const modal = document.getElementById('modal-container');
const saveItemsBtn = document.getElementById('save-btn');
const closeModal = document.querySelectorAll('.close-modal');

export let mergedItems;

items.forEach(category => {

    const section = document.createElement("div");
    section.className = "max-w-full mx-auto border border-teal-500 rounded-lg pb-4 pr-4 mb-4";

    const title = document.createElement("h2");
    title.className = "w-60 text-xl text-gray-50 font-bold px-4 py-2 bg-teal-600 rounded-br-xl rounded-tl-lg";
    title.textContent = category.catName + " Items";

    const buttonWrapper = document.createElement("div");
    buttonWrapper.className = "ml-4 mt-5 grid grid-cols-1 md:grid-cols-3 gap-4";

    category.subcategories.forEach(subCat => {
        const button = document.createElement("button");
        button.type = "button";
        button.textContent = subCat.name;
        button.className = "item w-full p-10 bg-white rounded-lg border border-gray-200 shadow-md text-lg font-semibold hover:text-teal-500 hover:shadow-lg hover:shadow-teal-300 transition";

        button.addEventListener('click', () => {
            openSubcategoryModal(subCat);
        });

        buttonWrapper.appendChild(button);
    });

    section.appendChild(title);
    section.appendChild(buttonWrapper);
    container.appendChild(section);
});

function openSubcategoryModal(subcategory) {
    const modalTitle = document.getElementById("modal-title");
    const modalSubcategories = document.getElementById("items-subcat-container");

    modalTitle.textContent = subcategory.name;
    modalSubcategories.innerHTML = "";

    const itemsGrid = document.createElement("div");
    itemsGrid.className = "grid grid-cols-1 md:grid-cols-3 gap-4";

    subcategory.items.forEach(item => {
        
        const itemEl = document.createElement('div');

        itemEl.className = 'item-container w-full flex flex-col gap-1 pt-6 shadow-[0_5px_20px_rgba(0,0,0,0.25)] bg-teal-50 rounded-lg hover:bg-teal-100 transition';
        itemEl.innerHTML = ''
        itemEl.innerHTML = `
            <div class="px-6 text-center">
                <p class="item-name text-lg font-semibold text-gray-900">${item.name}</p>
                <p class="item-price text-sm font-bold text-teal-600 mt-1">₱${item.price}</p>
            </div>
            <div class="flex flex-row items-start gap-4 bg-teal-500 w-full p-4 rounded-b-lg rounded-tr-2xl">
                <div class="flex items-center jsutify-center">
                    <label for="item" class="text-white font-semibold">Qty:</label>
                    <input type="number" name="item[${item.id}]" value="0" min="0" class="item-amount w-20 text-white text-lg font-semibold focus:outline-none px-3 py-1">
                </div>
                <button type="button" class="minus-btn text-xl px-3 py-1 font-bold bg-gray-100 rounded-lg"><i class="fa-solid fa-minus text-teal-600"></i></button>
                <button type="button" class="addition-btn text-xl px-3 py-1 font-bold bg-gray-100 rounded-lg"><i class="fa-solid fa-plus text-teal-600"></i></button>
            </div>
        `;

        itemsGrid.appendChild(itemEl);
    });

    modalSubcategories.appendChild(itemsGrid);
    modal.classList.remove("hidden");
}

document.addEventListener("click", function (e) {

    if (e.target.closest(".addition-btn")) {
        const wrapper = e.target.closest(".w-full");
        const input = wrapper.querySelector(".item-amount");

        input.value = parseInt(input.value || 0) + 1;
    }

    if (e.target.closest(".minus-btn")) {
        const wrapper = e.target.closest(".w-full");
        const input = wrapper.querySelector(".item-amount");

        let current = parseInt(input.value || 0);
        if (current > 0) {
            input.value = current - 1;
        }
    }

});

function createCart() {
    const inputs = document.querySelectorAll(".item-amount");
    const cart = [];

    inputs.forEach(input => {
        if(parseInt(input === 0)) input.disabled = true;
        const quantity = parseInt(input.value || 0);
        if (quantity > 0) {
            const itemId = input.name.match(/\d+/)[0];
            const itemName = input.closest('.item-container').querySelector('.item-name').textContent;
            const itemPrice = input.closest('.item-container').querySelector('.item-price').textContent.replace('₱', '');

            cart.push({
                id: parseInt(itemId),
                name: itemName,
                quantity: quantity,
                price: parseInt(itemPrice)
            });
        }
    });

    return cart;
}


saveItemsBtn.addEventListener('click', () => {
    const cartItems = createCart();
    const mainForm = document.getElementById('playhouse-registration-form');

    // Get existing items or initialize as empty array
    let existingItems = [];
    if (mainForm.dataset.selectedMenuItems) {
        try {
            existingItems = JSON.parse(mainForm.dataset.selectedMenuItems);
        } catch (e) {
            existingItems = [];
        }
    }

    // Merge new items with existing items
    // Remove duplicates by ID and combine
    const itemMap = {};
    
    // Add existing items to map
    existingItems.forEach(item => {
        itemMap[item.id] = item;
    });
    
    // Add/update with new items
    cartItems.forEach(item => {
        itemMap[item.id] = item;
    });
    
    // Convert back to array
    mergedItems = Object.values(itemMap);

    // Store merged items in a data attribute on the form for persistence
    mainForm.dataset.selectedMenuItems = JSON.stringify(mergedItems);

    modal.classList.add('hidden');
});

closeModal.forEach(btn => {
    btn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });
});