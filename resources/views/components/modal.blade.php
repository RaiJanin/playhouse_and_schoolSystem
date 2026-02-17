<style>
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>


<div id="modal-container" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-8 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>

        <div class="inline-block z-50 bg-white align-middle items-center rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-5xl w-full relative">
            <div class="flex justify-end">
                <button type="button" class="close-modal bg-red-500 py-2 px-4 rounded-bl-lg"><i class="fa-solid fa-x text-white"></i></button>
            </div>
            <div class="flex flex-col bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Category 1</h3>
                        <div class="border-t-2 border-gray-300 w-full mt-3 mb-3"></div>
                        <div id="items-subcat-container" class="mt-2 p-4">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3 bg-gray-50 px-4 py-3 sm:px-6 flex flex-col sm:flex-row-reverse gap-3 sm:gap-0">
                <button type="button" id="save-btn" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-8 py-2 bg-white text-base font-medium text-gray-700 hover:border-teal-500 hover:bg-gray-100 hover:shadow-md hover:shadow-teal-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-300">
                    Save
                </button>
                <button type="button" class="close-modal w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
