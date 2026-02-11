<style>
    input::placeholder {
        color: #6b7280 !important;
        opacity: 1;
    }
    input::-webkit-input-placeholder {
        color: #6b7280 !important;
    }
    input:-moz-placeholder {
        color: #6b7280 !important;
    }
    input::-moz-placeholder {
        color: #6b7280 !important;
    }
</style>
<div class="p-4">
    <h2 class="font-bold text-2xl text-gray-800 mb-2 text-center">Parent Information</h2>
    <p class="text-center text-gray-600 mb-5 font-semibold">
        Please provide your details
    </p>
    <div class="space-y-4">
        <div>
            <label for="parentName" class="block text-base font-semibold text-gray-900 mb-2">First Name</label>
            <input type="text" id="parentName" name="parentName" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="John" required />
        </div>
        <div>
            <label for="parentLastName" class="block text-base font-semibold text-gray-900 mb-2">Last Name</label>
            <input type="text" id="parentLastName" name="parentLastName" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="Doe" required />
        </div>
        <div>
            <label for="parentEmail" class="block text-base font-semibold text-gray-900 mb-2">Email Address</label>
            <input type="email" id="parentEmail" name="parentEmail" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300" placeholder="john.doe@email.com"/>
        </div>
        <div>
            <label for="parentBirthday" class="block text-base font-semibold text-gray-900 mb-2">Birthday</label>
            <input type="date" id="parentBirthday" name="parentBirthday" class="bg-teal-100 w-full px-4 py-2 border-2 border-teal-500 shadow rounded-lg font-semibold focus:outline-none focus:border-cyan-400 focus:shadow-none transition-all duration-300"/>
        </div>
    </div>
    <div class="mt-6 pt-4 border-t-2 border-gray-200">
    <div class="flex items-start space-x-3">
        <input 
            type="checkbox" 
            id="confirmGuardian" 
            name="confirmGuardian"
            class="sr-only">
        
        <label for="confirmGuardian" class="cursor-pointer group">
            <span id="guardianIndicator" class="mr-1 inline-block w-5 text-base font-semibold text-gray-900 group-hover:text-teal-700 transition-colors">[]</span>
            <span class="text-base font-semibold text-gray-900 group-hover:text-teal-700 transition-colors">Guardian</span>
            <span class="block mt-1 text-sm font-normal text-gray-600">I confirm I am the legal guardian of the child</span>
        </label>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const checkbox = document.getElementById('confirmGuardian');
    const indicator = document.getElementById('guardianIndicator');

    checkbox.addEventListener('change', toggleIndicator);
    
    toggleIndicator();
    
    function toggleIndicator() {
        if (checkbox.checked) {
            indicator.textContent = '[✓]';
            indicator.classList.replace('text-gray-900', 'text-teal-700');
            indicator.classList.add('font-bold');
        } else {
            indicator.textContent = '[]';
            indicator.classList.replace('text-teal-700', 'text-gray-900');
            indicator.classList.remove('font-bold');
        }
    }
    
    document.querySelector('label[for="confirmGuardian"]').addEventListener('mousedown', () => {
        indicator.style.transform = 'scale(1.1)';
        setTimeout(() => { indicator.style.transform = ''; }, 100);
    });
});
</script>
</div>


