@extends('layout.content')

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

@section('contents')
    <div class="step" id="step4">
        <div class="max-w-md mx-auto bg-[#f8fdfd] rounded-3xl p-8 shadow-sm border border-teal-50">
            <h2 class="text-3xl font-bold text-center text-slate-800 mb-2">Children Information</h2>
            <p class="text-center text-teal-600 font-medium mb-8">Add child details and select playtime duration</p>

            <form id="childrenForm" class="space-y-5">
                <div id="childrenContainer" class="space-y-6">
                    <div class="child-entry space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">First Name</label>
                            <input type="text" class="child-first w-full px-4 py-3 bg-white border-2 border-teal-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 text-slate-700" placeholder="Jane" />
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Last Name</label>
                            <input type="text" class="child-last w-full px-4 py-3 bg-white border-2 border-teal-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 text-slate-700" placeholder="Doe" />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Birthday</label>
                            <input type="date" class="child-bday w-full px-4 py-3 bg-white border-2 border-teal-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 text-slate-700" />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Playtime Duration</label>
                            <div class="relative">
                                <select class="child-duration w-full px-4 py-3 bg-white border-2 border-teal-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 text-slate-700 appearance-none cursor-pointer">
                                    <option value="1">1 Hour - ₱500</option>
                                    <option value="2">2 Hours - ₱800</option>
                                    <option value="3">3 Hours - ₱1,100</option>
                                    <option value="unlimited">Unlimited - ₱1,200</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-teal-500">
                                    <i class="fa-solid fa-chevron-down text-sm"></i>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="button" class="remove-child text-xs text-red-400 hover:text-red-600 hidden">Remove</button>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" id="addChildBtn" class="text-sm font-bold text-teal-500 hover:text-teal-700 flex items-center gap-1">
                        <i class="fa-solid fa-plus text-xs"></i> Add another child
                    </button>
                </div>

                <div class="flex space-x-4 pt-6">
                    <a href="{{ route('playhouse.parent') }}" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-600 font-bold py-3 px-4 rounded-xl text-center transition-all">Back</a>
                    <button type="button" id="childrenNext" class="flex-1 bg-teal-500 hover:bg-teal-600 text-white font-bold py-3 px-4 rounded-xl shadow-md transition-all">Next</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('section-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('childrenContainer');
    const addBtn = document.getElementById('addChildBtn');
    const nextBtn = document.getElementById('childrenNext');

    function createChildEntry() {
        const entry = document.createElement('div');
        entry.className = 'child-entry space-y-4 pt-6 border-t border-teal-50 mt-6';
        entry.innerHTML = `
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">First Name</label>
                <input type="text" class="child-first w-full px-4 py-3 bg-white border-2 border-teal-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 text-slate-700" placeholder="Jane" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Last Name</label>
                <input type="text" class="child-last w-full px-4 py-3 bg-white border-2 border-teal-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 text-slate-700" placeholder="Doe" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Birthday</label>
                <input type="date" class="child-bday w-full px-4 py-3 bg-white border-2 border-teal-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 text-slate-700" />
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Playtime Duration</label>
                <div class="relative">
                    <select class="child-duration w-full px-4 py-3 bg-white border-2 border-teal-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 text-slate-700 appearance-none cursor-pointer">
                        <option value="1">1 Hour - ₱500</option>
                        <option value="2">2 Hours - ₱800</option>
                        <option value="3">3 Hours - ₱1,100</option>
                        <option value="unlimited">Unlimited - ₱1,200</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-teal-500">
                        <i class="fa-solid fa-chevron-down text-sm"></i>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="button" class="remove-child text-xs text-red-400 hover:text-red-600 transition-colors">Remove</button>
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
@endsection