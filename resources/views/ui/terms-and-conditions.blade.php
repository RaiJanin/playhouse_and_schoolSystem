<x-breeze-modal name="terms-and-conditions" :show="false" maxWidth="7xl">
    <div class="p-4 sm:p-6 max-h-[80vh] overflow-y-auto" x-data="{ isChecked: false }">
        <h2 class="text-xl font-bold mb-4 text-center">WAIVER AND LIABILITY RELEASE</h2>
        <p class="text-sm text-gray-600 mb-4">Please read this form carefully before signing. By signing below, you confirm that you have read, understood, and agreed to all terms stated. This waiver will be used as evidence.</p>

        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <h3 class="font-semibold mb-3">SECTION I. INFORMATION</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                <div>
                    <span class="font-medium">Child's Name:</span> _________________________
                </div>
                <div>
                    <span class="font-medium">Age:</span> ____ <span class="font-medium">Gender:</span> <span class="mx-1">M</span> <span class="mx-1">F</span>
                </div>
                <div>
                    <span class="font-medium">Birthday:</span> _________________________
                </div>
                <div>
                    <span class="font-medium">Special Needs/Allergies:</span> _________________________
                </div>
                <div class="sm:col-span-2">
                    <span class="font-medium">Number of Guardians:</span> _________________________
                </div>
                <div class="sm:col-span-2">
                    <span class="font-medium">Parent/Guardians Full Name(s):</span> _________________________
                </div>
                <div class="sm:col-span-2">
                    <span class="font-medium">Relationship to Child:</span> _________________________
                </div>
                <div class="sm:col-span-2">
                    <span class="font-medium">Contact Number:</span> _________________________
                </div>
                <div class="sm:col-span-2">
                    <span class="font-medium">Emergency Contact Name:</span> _________________________
                </div>
                <div class="sm:col-span-2">
                    <span class="font-medium">Emergency Contact Number:</span> _________________________
                </div>
                <div>
                    <span class="font-medium">Date of Visit:</span> _________________________
                </div>
                <div>
                    <span class="font-medium">Time Start:</span> _______ <span class="font-medium">Time End:</span> _______
                </div>
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <h3 class="font-semibold mb-3">SECTION II. PLAY AREA RULES</h3>
            <p class="text-sm text-gray-600 mb-3">By signing this waiver, you confirm that you have read the following rules and agree to ensure your child follows them during their visit.</p>
            
            <div class="space-y-2 text-sm text-gray-700">
                <p><span class="font-medium">1. Acknowledgment of Risks:</span> By entering, you and your minors (the "Guest") acknowledge that play areas and cafés have inherent risks, such as falls, collisions, and illness. You voluntarily accept all known and unknown risks associated with these activities.</p>
                
                <p><span class="font-medium">2. Guardian Responsibilities:</span></p>
                <ul class="list-disc list-inside ml-2 space-y-1">
                    <li><span class="font-medium">Active Supervision:</span> Children under 12 must be supervised by an adult (18+) at all times.</li>
                    <li><span class="font-medium">Rule Enforcement:</span> Guardians must ensure children wear socks at all times, remove any sharp accessories (e.g. sharp jewelries, hair clips, keychains, etc.) and follow all "Who Can Play" and "Inside the Play Area" rules at all times.</li>
                    <li><span class="font-medium">Behavior:</span> Guardians are responsible for immediately stopping their child/children's rough or harmful play.</li>
                </ul>
                
                <p><span class="font-medium">3. Health and Safety:</span> You attest that no one in your party has any contagious conditions (e.g. fever, flu, HFMD, chickenpox, rashes, etc.). Mimo Town reserves the right to refuse entry or ask guests to leave if they appear unwell.</p>
                
                <p><span class="font-medium">4. Limitation of Liability:</span> By agreeing, you take full responsibility for your child and waive the right to hold Mimo Play Cafe liable for any injuries or damages, unless they are caused by the staff's gross negligence. Mimo Town is also not liable for:</p>
                <ul class="list-disc list-inside ml-2 space-y-1">
                    <li><span class="font-medium">Belongings:</span> Lost, stolen, or damaged items (even those in lockers).</li>
                    <li><span class="font-medium">Prohibited Items:</span> Damage or stains caused by guest-brought items like slime or markers.</li>
                </ul>
                
                <p><span class="font-medium">5. Indemnification & Removal:</span></p>
                <ul class="list-disc list-inside ml-2 space-y-1">
                    <li><span class="font-medium">Right of Removal:</span> Staff can remove guests for rule violations without a refund.</li>
                    <li><span class="font-medium">Indemnity:</span> You agree to cover any legal fees or damages Mimo Town incurs due to your negligence or failure to follow rules.</li>
                </ul>
                
                <p class="font-medium text-red-600 mt-2">Please be guided by the additional fees:</p>
                <ul class="list-none ml-2 space-y-1 text-sm">
                    <li>• Accidental pee inside the play area: Php 10,000</li>
                    <li>• Accidental vomit: Php 15,000</li>
                    <li>• Accidental poop: Php 25,000</li>
                </ul>
                
                <p class="mt-2 text-sm"><span class="font-medium">Note:</span> Always follow staff instructions. Failure to follow these rules may result in being asked to leave the premises.</p>
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <div class="space-y-2 text-sm text-gray-700">
                <label class="flex items-start gap-2">
                    {{-- <input type="checkbox" class="mt-1 waiver-checkbox"> --}}
                    <i class="terms-agreement fa-regular fa-square"></i>
                    <span>I agree to supervise my child at all times and take full responsibility for their behavior.</span>
                </label>
                <label class="flex items-start gap-2">
                    {{-- <input type="checkbox" class="mt-1 waiver-checkbox"> --}}
                    <i class="terms-agreement fa-regular fa-square"></i>
                    <span>I voluntarily choose to allow my child to participate in play area activities with full awareness of these risks. I will not hold Mimo Town liable for injuries or loss of property.</span>
                </label>
                <label class="flex items-start gap-2">
                    {{-- <input type="checkbox" class="mt-1 waiver-checkbox"> --}}
                    <i class="terms-agreement fa-regular fa-square"></i>
                    <span>I agree not to bring food, drinks, sharp materials/accessories, and/or messy materials (slime, clay, etc.) into the play zones.</span>
                </label>
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <h3 class="font-semibold mb-3">Health Declaration</h3>
            <div class="space-y-2 text-sm text-gray-700">
                <label class="flex items-start gap-2">
                    {{-- <input type="checkbox" class="mt-1 waiver-checkbox"> --}}
                    <i class="terms-agreement fa-regular fa-square"></i>
                    <span>I confirm that the child and guardian are currently free from contagious conditions like flu, fever, or contagious skin/eye conditions.</span>
                </label>
                <label class="flex items-start gap-2">
                    {{-- <input type="checkbox" class="mt-1 waiver-checkbox"> --}}
                    <i class="terms-agreement fa-regular fa-square"></i>
                    <span>I understand that Mimo Town reserves the right to refuse entry if a guest appears unwell.</span>
                </label>
                <label class="flex items-start gap-2">
                    {{-- <input type="checkbox" class="mt-1 waiver-checkbox"> --}}
                    <i class="terms-agreement fa-regular fa-square"></i>
                    <span>I agree to the terms and conditions.</span>
                </label>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-sm">
                <div class="flex-1">
                    <p class="mb-1">Signature over printed name</p>
                    <div class="border-b border-gray-400 h-6"></div>
                </div>
                <div class="w-40">
                    <p class="mb-1">Date</p>
                    <div class="border-b border-gray-400 h-6"></div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-3">
            <x-secondary-button x-on:click="$dispatch('close')">
                Close
            </x-secondary-button>
        </div>
    </div>
</x-breeze-modal>