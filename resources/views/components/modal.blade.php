<style>
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    /* Custom scrollbar for modal content */
    #items-subcat-container::-webkit-scrollbar {
        width: 8px;
    }

    #items-subcat-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    #items-subcat-container::-webkit-scrollbar-thumb {
        background: #14b8a6;
        border-radius: 4px;
    }

    #items-subcat-container::-webkit-scrollbar-thumb:hover {
        background: #0d9488;
    }
</style>


<div id="modal-container" class="fixed z-50 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal Panel -->
        <div class="inline-block z-50 bg-white align-bottom rounded-2xl shadow-2xl transform transition-all sm:my-8 sm:max-w-4xl w-full relative overflow-hidden">
            <!-- Header with gradient -->
            <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <h3 class="modal-title text-xl font-bold text-white" id="modal-title">Edit Information</h3>
                </div>
                <button type="button" class="close-modal bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="px-6 py-4 max-h-[60vh] overflow-y-auto" id="items-subcat-container">
            </div>

            <!-- Footer -->
            <div class="modal-footer bg-gray-50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3 border-t border-gray-100">
                <button type="button" id="save-btn" class="flex items-center justify-center gap-2 w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-teal-500 to-teal-600 text-white font-semibold rounded-xl hover:from-teal-600 hover:to-teal-700 shadow-lg shadow-teal-500/30 transition-all duration-300 transform hover:scale-[1.02]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Changes
                </button>
                <button type="button" class="close-modal flex items-center justify-center gap-2 w-full sm:w-auto px-6 py-2.5 bg-white text-gray-700 font-semibold rounded-xl border-2 border-gray-200 hover:border-red-400 hover:text-red-500 hover:bg-red-50 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
