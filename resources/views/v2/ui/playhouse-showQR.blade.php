<div class="p-3 flex flex-col">
    <h2 class="font-bold text-2xl text-gray-800 mb-2 text-center">Registration Complete</h2>
    <p class="text-center text-gray-600 mb-2 font-semibold">
        Your registration was successful! Scan the QR code below to access your account.
    </p>
    
    <div class="mb-4 px-5 flex justify-center items-center">
        <div class="flex flex-col items-center bg-white p-3 rounded-xl shadow-lg border-2 border-gray-200">
            <div class="flex flex-col items-center">
                <p class="text-gray-600 text-lg font-semibold">Order #</p>
                <h2 id="order-number-text" class="text-2xl font-bold">-0000-</h2>
            </div>
            <div id="qr-image" class="p-4">
                <div class="flex flex-col items-center p-20">- - -</div>
            </div>
        </div>
    </div>
    
    <div class="px-5">
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
            <p class="text-sm text-green-800 font-medium">
                Please save this QR code for future reference.
            </p>
        </div>
    </div>
    <div class="flex flex-col items-center gap-3 mt-6 mb-2">
        <a id="order-info-link" href="#" target="_blank" class="text-teal-600 hover:text-teal-700 font-medium text-sm underline hidden">View Invoice</a>
        <div class="flex flex-col mt-2">
            <button type="button" onclick="window.location.href=`{{ route('v2.playhouse.registration') }}`" class="bg-cyan-600 text-white px-6 py-2 rounded-md font-semibold text-lg cursor-pointer shadow hover:bg-cyan-500 focus:ring-2 focus:ring-offset-2 ring-cyan-500 disabled:cursor-not-allowed disabled:bg-cyan-400 disabled:shadow-none transition-all duration-300">Create Another Registration</button>
        </div>
    </div>
</div> 