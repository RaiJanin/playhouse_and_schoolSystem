@extends('v2.layout.app')

@section('title', 'Mimo Play Cafe - Checkout')

@section('main-content')
    @include('components.backdrop')
    <div class="container max-w-full mx-auto">
        @include('v2.ui.partials.header')
        
        <div class="max-w-2xl mx-auto mt-8 px-4">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-[#0d9984] mb-6 text-center">Check Out</h2>
                
                <!-- Search Form -->
                <form id="checkout-search-form" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
                        <input 
                            type="tel" 
                            id="search-phone" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d9984] focus:border-transparent outline-none"
                            placeholder="Enter phone number"
                        >
                    </div>
                    
                    <div class="text-center text-gray-500 font-medium">- OR -</div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Guardian/Parent Name</label>
                        <input 
                            type="text" 
                            id="search-guardian" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d9984] focus:border-transparent outline-none"
                            placeholder="Enter guardian or parent name"
                        >
                    </div>
                    
                    <button 
                        type="submit"
                        class="w-full bg-[#0d9984] text-white font-bold py-3 px-6 rounded-lg hover:bg-[#0a7a6a] transition-colors"
                    >
                        Search
                    </button>
                </form>

                <!-- Search Results -->
                <div id="search-results" class="mt-8 hidden">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Active Check-ins</h3>
                    <div id="orders-list" class="space-y-4">
                        <!-- Orders will be dynamically inserted here -->
                    </div>
                </div>

                <!-- No Results Message -->
                <div id="no-results" class="mt-8 text-center text-gray-500 hidden">
                    <p class="text-lg">No active check-ins found.</p>
                </div>

                <!-- Loading Indicator -->
                <div id="loading" class="mt-8 text-center hidden">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-[#0d9984] border-t-transparent"></div>
                    <p class="text-gray-600 mt-2">Searching...</p>
                </div>

                <!-- Error Message -->
                <div id="error-message" class="mt-6 p-4 bg-red-100 text-red-700 rounded-lg hidden"></div>
            </div>
        </div>

        <!-- Success Modal -->
        <div id="success-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-2xl p-8 max-w-md mx-4 text-center">
                <div class="text-5xl mb-4">✅</div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Check Out Complete!</h3>
                <div id="checkout-details" class="text-left bg-gray-100 p-4 rounded-lg mt-4"></div>
                <button 
                    onclick="document.getElementById('success-modal').classList.add('hidden')"
                    class="mt-6 bg-[#0d9984] text-white font-bold py-3 px-8 rounded-lg hover:bg-[#0a7a6a] transition-colors"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Define API routes directly (simulating what app.js exposes)
    window.API_ROUTES = {
        submitURL: '/api/submit/whole-form',
        makeOtpURL: '/api/submit/make-otp',
        verifyOtpURL: '/api/verify-otp',
        searchReturneeURL: '/api/search-returnee',
        deleteOtpURL: '/api/delete-otp',
        checkPhoneURL: '/api/check-phone',
        getOrdersURL: '/api/get-orders',
        checkOutURL: '/api/check-out'
    };
    
    // Simple submitData function
    window.submitData = async function(apiLink, dataObject, method = 'POST', routeParam = null) {
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
    };
    
    document.addEventListener('DOMContentLoaded', function() {
        // API_ROUTES is defined globally above
        const API_ROUTES = window.API_ROUTES;
        const submitData = window.submitData;
        
        const searchForm = document.getElementById('checkout-search-form');
            const searchResults = document.getElementById('search-results');
            const ordersList = document.getElementById('orders-list');
            const noResults = document.getElementById('no-results');
            const loading = document.getElementById('loading');
            const errorMessage = document.getElementById('error-message');
            const successModal = document.getElementById('success-modal');

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

                try {
                    // Build URL with query parameters
                    const baseUrl = API_ROUTES.getOrdersURL;
                    const params = new URLSearchParams();
                    if (phone) params.append('ph_num', phone);
                    if (guardian) params.append('grdian_name', guardian);
                    
                    const fullUrl = `${baseUrl}?${params.toString()}`;

                    const response = await fetch(fullUrl, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        }
                    });
                    
                    const data = await response.json();
                    
                    loading.classList.add('hidden');
                    
                    if (data.orders && data.orders.length > 0) {
                        displayOrders(data.orders);
                    } else {
                        noResults.classList.remove('hidden');
                    }
                } catch (error) {
                    loading.classList.add('hidden');
                    showError('Error searching for orders: ' + error.message);
                }
            });

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
                            onclick="handleCheckout('${order.ord_code_ph}')"
                            class="w-full bg-[#0d9984] text-white font-bold py-2 px-4 rounded-lg hover:bg-[#0a7a6a] transition-colors"
                        >
                            Check Out
                        </button>
                    `;
                    
                    ordersList.appendChild(orderCard);
                });
            }

            window.handleCheckout = async function(orderNumber) {
                if (!confirm('Are you sure you want to check out this order?')) {
                    return;
                }

                loading.classList.remove('hidden');
                searchResults.classList.add('hidden');

                try {
                    const response = await submitData(API_ROUTES.checkOutURL, null, 'PATCH', orderNumber);
                    
                    loading.classList.add('hidden');

                    if (response.checked_out) {
                        // Show success modal
                        const detailsHtml = `
                            <p><strong>Order #:</strong> ${orderNumber}</p>
                            <p><strong>Status:</strong> Successfully checked out</p>
                            ${response.extrCharge > 0 ? `<p><strong>Extra Charges:</strong> ₱${response.extrCharge}</p>` : ''}
                            <p><strong>Total Amount:</strong> ₱${response.order.total_amnt}</p>
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

            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.classList.remove('hidden');
            }
    });
</script>
@endsection
