<div class="p-3 flex flex-col">
    <h2 class="font-bold text-2xl text-gray-800 mb-2 text-center">Registration Complete</h2>
    <p class="text-center text-gray-600 mb-5 font-semibold">
        Your registration was successful! Scan the QR code below to access your account or save this information for future reference.
    </p>
    
    <div class="mb-5 px-5 flex justify-center">
        <div class="bg-white p-5 rounded-xl shadow-lg border-2 border-gray-200">
            <img 
                src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=Registration+Successful" 
                alt="QR Code" 
                class="w-48 h-48 rounded-lg"
            />
            <p class="text-center text-sm font-medium text-gray-600 mt-3">Scan this to POS</p>
        </div>
    </div>
    
    <div class="px-5">
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
            <p class="text-sm text-green-800 font-medium">
                Please save this QR code for future reference.
            </p>
        </div>
    </div>
    <div class="flex items-center justify-center mt-8 mb-2">
        <div class="flex flex-col mt-2">
            <button type="button" onclick="window.location.href=`{{ route('playhouse.registration') }}`" class="bg-cyan-600 text-white px-6 py-2 rounded-md font-semibold text-lg cursor-pointer shadow hover:bg-cyan-500 focus:ring-2 focus:ring-offset-2 ring-cyan-500 disabled:cursor-not-allowed disabled:bg-cyan-400 disabled:shadow-none transition-all duration-300">Create Another Registration</button>
        </div>
    </div>
</div> 