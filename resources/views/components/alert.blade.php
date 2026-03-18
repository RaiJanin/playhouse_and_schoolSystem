<!-- From Uiverse.io by seyed-mohsen-mousavi -->
<div id="alert-modal" class="flex fixed items-center justify-center p-4 z-50 w-full animate-fadeIn" hidden>
    <div class="flex flex-col gap-2 w-80 sm:w-94 text-[10px] sm:text-sm">
        <div class="error-alert cursor-default flex items-center justify-between w-full rounded-lg bg-[#232531] p-3">
            <div class="flex gap-2">
                <div class="text-[#d65563] bg-white/5 backdrop-blur-xl p-1 rounded-lg h-8">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"></path>
                    </svg>
                </div>
                <div>
                    <p class="error-msg text-gray-50"></p>
                </div>
            </div>
            <button id="alert-modal" type="button" class="close-err-msg text-gray-600 hover:bg-white/10 p-1 rounded-md transition-colors ease-linear">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</div>
<div id="alert-container" class="fixed top-5 left-1/2 transform -translate-x-1/2 z-50 space-y-3 max-w-sm w-full px-4"></div>
