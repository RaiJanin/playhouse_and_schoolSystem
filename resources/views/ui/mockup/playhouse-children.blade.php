<style>
    input::placeholder {
        color: #9ca3af !important;
        opacity: 1;
    }
    input {
        -webkit-appearance: none;
        appearance: none;
    }
</style>

<div class="p-4">
    <h2 class="font-bold text-2xl text-gray-800 mb-2 text-center">Children Information</h2>
    <p class="text-center text-gray-600 mb-5 font-semibold">
        Add child details and select playtime duration
    </p>
    <div id="childrenForm" class="space-y-5">
        <div id="childrenContainer" class="space-y-6">
            <div class="child-entry space-y-4">
                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">First Name</label>
                    <input type="text" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="Jane" />
                </div>
                
                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Last Name</label>
                    <input type="text" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="Doe" />
                </div>

                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Birthday</label>
                    <input type="date" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" />
                </div>

                <div>
                    <label class="block text-base font-semibold text-gray-900 mb-2">Playtime Duration</label>
                    <div class="relative">
                        <select class="child-duration bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300 cursor-pointer appearance-none">
                            <option value="1">1 Hour</option>
                            <option value="2">2 Hours</option>
                            <option value="3">3 Hours</option>
                            <option value="4">4 Hours</option>
                            <option value="unlimited">Unlimited</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-teal-500">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-2">
            <button type="button" id="addChildBtn" class="text-base font-bold text-teal-500 hover:text-teal-700 flex items-center gap-1">
                <i class="fa-solid fa-plus text-xs"></i> Add another child
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('childrenContainer');
    const addBtn = document.getElementById('addChildBtn');
    const nextBtn = document.getElementById('childrenNext');

    function createChildEntry() {
        const entry = document.createElement('div');
        entry.className = 'child-entry space-y-4 pt-6 border-t border-gray-600 mt-6';
        entry.innerHTML = `
            <div>
                <label class="block text-base font-semibold text-gray-900 mb-2">First Name</label>
                <input type="text" class="w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="Jane" />
            </div>
            
            <div>
                <label class="block text-base font-semibold text-gray-900 mb-2">Last Name</label>
                <input type="text" class="w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="Doe" />
            </div>

            <div>
                <label class="block text-base font-semibold text-gray-900 mb-2">Birthday</label>
                <input type="date" class="w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" />
            </div>

            <div>
                <label class="block text-base font-semibold text-gray-900 mb-2">Playtime Duration</label>
                <div class="relative">
                    <select class="child-duration w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300 cursor-pointer appearance-none">
                        <option value="1">1 Hour</option>
                        <option value="2">2 Hours</option>
                        <option value="3">3 Hours</option>
                        <option value="4">4 Hours</option>
                        <option value="unlimited">Unlimited</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-teal-500">
                        <i class="fa-solid fa-chevron-down text-sm"></i>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="button" class="remove-child text-base font-semibold text-slate-600 hover:text-red-600 transition-colors">Remove</button>
            </div>
        `;
        attachEntryListeners(entry);
        return entry;
    }

    function attachEntryListeners(entry) {
        const removeBtn = entry.querySelector('.remove-child');
        if (removeBtn) {
            removeBtn.addEventListener('click', () => {
                entry.remove();
                updateRemoveButtons();
            });
        }
    }

    function updateRemoveButtons() {
        const entries = container.querySelectorAll('.child-entry');
        entries.forEach((e) => {
            const btn = e.querySelector('.remove-child');
            if (btn) btn.style.display = (entries.length > 1 ? 'inline' : 'none');
        });
    }

    addBtn.addEventListener('click', () => {
        const newEntry = createChildEntry();
        container.appendChild(newEntry);
        newEntry.querySelector('.child-first').focus();
        updateRemoveButtons();
    });

    nextBtn.addEventListener('click', () => {
        window.location.href = '{{ route("playhouse.duration") }}';
    });
});
</script>