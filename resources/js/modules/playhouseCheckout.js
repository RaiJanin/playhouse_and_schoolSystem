import '../config/masterFile.js';

import { API_ROUTES } from "../config/api.js";
import { showConsole } from "../config/debug.js";
import { 
    getOrDelete,
    submitData
} from "../services/requestApi.js";

document.addEventListener('DOMContentLoaded', () => {
    const searchForm = document.getElementById('checkout-search-form');
    const searchResults = document.getElementById('search-results');
    const ordersList = document.getElementById('orders-list');
    const noResults = document.getElementById('no-results');
    const loading = document.getElementById('loading');
    const errorMessage = document.getElementById('error-message');
    const successModal = document.getElementById('success-modal');
    const orderModal = document.getElementById('modal-container');
    orderModal.querySelector('.modal-footer').classList.add('hidden');

    searchForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Reset UI
        searchResults.classList.add('hidden');
        noResults.classList.add('hidden');
        errorMessage.classList.add('hidden');
        ordersList.innerHTML = '';
        
        const phone = document.getElementById('search-phone').value.trim();
        const guardian = document.getElementById('search-guardian').value.trim();
        
        if (!phone && !guardian) {
            showError('Please enter either a phone number or a guardian/parent name.');
            return;
        }

        loading.classList.remove('hidden');
        
        const data = await getOrDelete('GET', `${API_ROUTES.getOrdersURL}?ph_num=${phone}&grdian_name=${guardian}`, []);
        showConsole('log', 'Orders: ', data);
            
        loading.classList.add('hidden');
        
        if (data.not_found) {
            showError(data.message);
            return;
        }
        
        if (data.orders && data.orders.length >= 1) {
            displayOrders(data.orders);
        } else {
            noResults.classList.remove('hidden');
        }
        
    });

    window.handleCheckout = async function (orderNumber) {
        if (!confirm('Are you sure you want to check out this order?')) {
            return;
        }

        loading.classList.remove('hidden');
        searchResults.classList.add('hidden');

        try {
            const response = await submitData(API_ROUTES.checkOutURL, null, 'PATCH', orderNumber);
            showConsole('log', 'Child checked out: ', response);
            
            loading.classList.add('hidden');

            if (response.checked_out) {
                // Build detailed HTML per order item
                let itemsHtml = '';
                response.order.order_items.forEach(item => {
                    const subtotal = Number(item.durationsubtotal) + Number(item.socksprice);
                    const extraCharge = Number(item.lne_xtra_chrg || 0);
                    const total = subtotal + extraCharge;

                    // Calculate extra charge breakdown for clarity
                    const overtimeMinutes = Math.max(0, (new Date(item.updated_at) - new Date(item.created_at)) / 60000 - item.durationhours * 60);
                    const chargeUnits = Math.ceil(overtimeMinutes / window.masterfile.minutesPerCharge);

                    itemsHtml += `
                        <div class="mb-4 border-b border-gray-200 pb-2">
                            <p><strong>Child:</strong> ${item.child?.firstname || 'N/A'} ${item.child?.lastname || ''}</p>
                            <p><strong>Play Duration:</strong> ${item.durationhours} hr(s) — ₱${Number(item.durationsubtotal).toFixed(2)}</p>
                            <p><strong>Socks:</strong> ₱${Number(item.socksprice).toFixed(2)}</p>
                            <p><strong>Subtotal:</strong> ₱${subtotal.toFixed(2)}</p>
                            ${extraCharge > 0 ? `
                                <div class="text-red-500">
                                    <p><strong>Extra Charges:</strong> ₱${extraCharge.toFixed(2)}</p>
                                    <p>Overtime: ${Math.max(0, Math.round(overtimeMinutes))} minute(s)</p>
                                    <p>Charge units: ${chargeUnits} × ₱${window.masterfile.chargeOfMinutes} per ${window.masterfile.minutesPerCharge} min</p>
                                </div>
                            ` : ''}
                            <p><strong>Total:</strong> ₱${total.toFixed(2)}</p>
                        </div>
                    `;
                });

                const detailsHtml = `
                    <p><strong>Order #:</strong> ${orderNumber}</p>
                    <p><strong>Guardian:</strong> ${response.order.guardian}</p>
                    ${itemsHtml}
                    <p><strong>Order Total:</strong> ₱${Number(response.order.total_amnt).toFixed(2)}</p>
                `;

                document.getElementById('checkout-details').innerHTML = detailsHtml;
                successModal.classList.remove('hidden');
            } else {
                showError(response.message || 'Checkout failed. Please try again.');
                searchResults.classList.remove('hidden');
            }
        } catch (error) {
            loading.classList.add('hidden');
            showError('Error during checkout: ' + error.message);
            searchResults.classList.remove('hidden');
        }
    };

    orderModal.querySelector('.close-modal').addEventListener('click', () => {
        orderModal.classList.add('hidden');
    })

    function viewOrder(btn) {
        const items = JSON.parse(btn.dataset.items);
        const orderNum = btn.dataset.orderNum;
        const orderItemCardHtml = document.getElementById('items-subcat-container');
        orderItemCardHtml.innerHTML = '';
        showConsole('log', 'Order Items: ', items);
        orderModal.classList.remove('hidden');
        orderModal.querySelector('.modal-title').textContent = orderNum;

        orderItemCardHtml.innerHTML = items.map(item => {

            const details = computeExtraChargeDetails(item);

            return `
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-300 mb-3">
                    <p class="font-medium text-gray-800">
                        ${item.child.firstname} ${item.child.lastname}
                    </p>
                    <div class="mt-2 space-y-1 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Play duration</span>
                            <span>${item.durationhours} hr(s) — ₱${Number(item?.durationsubtotal).toFixed(2) }</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span>Socks</span>
                            <span>₱${Number(item?.socksprice).toFixed(2) || ''}</span>
                        </div>
                        
                    </div>
                    <div class="mt-2 pt-2 border-t border-gray-200 flex justify-between font-medium text-gray-800">
                        <span>Subtotal</span>
                        <span>₱${details.subtotal.toFixed(2)}</span>
                    </div>

                    
                    <div class="mt-2 text-red-500">
                        <div class="flex justify-between">
                            <span>Extra Charge (Preview)</span>
                            <span>₱${details.extraCharge.toFixed(2)}</span>
                        </div>
                        <div class="text-sm text-gray-600 mt-1">
                            <p>Overtime: ${details.extraMinutes} minute(s) (actual ${details.actualMinutes} - paid ${details.paidMinutes})</p>
                            <p>Rate: ₱${window.masterfile.chargeOfMinutes} per ${window.masterfile.minutesPerCharge} minutes</p>
                            <p>Number of 2-minute blocks: ${details.chargeUnits}</p>
                            <p>Extra Charge = ${details.chargeUnits} × ₱${window.masterfile.chargeOfMinutes} = ₱${details.extraCharge.toFixed(2)}</p>
                        </div>
                    </div>
                    

                    <div class="mt-1 pt-1 border-t border-gray-200 flex justify-between font-semibold">
                        <span>Total (Preview)</span>
                        <span>₱${details.totalWithExtra.toFixed(2)}</span>
                    </div>
                    <button 
                        data-check-out-id="${item.id}"
                        class="check-out-btn mt-3 w-full bg-[#0d9984] text-white font-bold py-2 px-4 rounded-lg hover:bg-[#0a7a6a] transition-colors"
                    >
                        Check out
                    </button>
                </div>
            `;
        }).join('');

        orderItemCardHtml.querySelectorAll('.check-out-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const orderItemId = e.currentTarget.dataset.checkOutId;
                showConsole('log', 'Check out ID: ', orderItemId);
                orderModal.classList.add('hidden');
                handleCheckout(orderItemId);
            });
        });
    }
    window.viewOrder = viewOrder;

    function displayOrders(orders) {
        searchResults.classList.remove('hidden');
        
        orders.forEach(order => {
            const orderCard = document.createElement('div');
            orderCard.className = 'bg-gray-50 border border-gray-200 rounded-lg p-4';
            
            const orderItems = order.order_items || [];
            const childrenCount = orderItems.length;
            
            let itemsHtml = '';
            orderItems.forEach(item => {
                const duration = item.durationhours == 5 ? 'Unlimited' : item.durationhours + ' hours';
                const socks = item.socksqty > 0 ? `, ${item.socksqty} pair(s) of socks` : '';
                itemsHtml += `<li class="text-gray-600">${duration}${socks}</li>`;
            });

            orderCard.innerHTML = `
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h4 class="font-bold text-gray-800">Order #${order.ord_code_ph}</h4>
                        <p class="text-sm text-gray-600">Guardian: ${order.guardian}</p>
                        <p class="text-sm text-gray-600">Total: ₱${order.total_amnt}</p>
                    </div>
                    <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                        ${childrenCount} child(ren)
                    </span>
                    
                </div>
                <ul class="mb-4 ml-4">
                    ${itemsHtml}
                </ul>
                <button 
                    data-items='${JSON.stringify(order.order_items)}'
                    data-orderNum='${order.ord_code_ph}'
                    onclick="viewOrder(this)"
                    class="w-full bg-[#0d9984] text-white font-bold py-2 px-4 rounded-lg hover:bg-[#0a7a6a] transition-colors"
                >
                    View Order
                </button>
            `;
            
            ordersList.appendChild(orderCard);
        });
    }

    function computeExtraChargeDetails(item) {
        const subtotal = Number(item?.durationsubtotal) + Number(item?.socksprice);

        // times
        const checkIn = new Date(item.created_at);
        const now = new Date(); // can use current time or simulate checkout
        const paidMinutes = item.durationhours * 60;

        // actual minutes stayed
        const actualMinutes = Math.ceil((now - checkIn) / 60000);

        let extraMinutes = 0;
        let chargeUnits = 0;
        let extraCharge = 0;

        if (actualMinutes > paidMinutes) {
            extraMinutes = actualMinutes - paidMinutes;
            chargeUnits = Math.ceil(extraMinutes / window.masterfile.minutesPerCharge);
            extraCharge = chargeUnits * window.masterfile.chargeOfMinutes;
        }

        return {
            subtotal,
            actualMinutes,
            paidMinutes,
            extraMinutes,
            chargeUnits,
            extraCharge,
            totalWithExtra: subtotal + extraCharge
        };
    }

    function showError(message) {
        errorMessage.textContent = message;
        errorMessage.classList.remove('hidden');
    }

});