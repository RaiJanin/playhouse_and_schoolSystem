@extends('layout.content')

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

@section('contents')
    <div class="step" id="step3">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-4">Parent Information</h2>
        <p class="text-center text-gray-800 text-lg mb-6">Please provide your details</p>
        <div class="space-y-4">
            <div>
                <label for="parentName" class="block text-base font-semibold text-gray-900 mb-2">First Name</label>
                <input type="text" id="parentName" class="w-full px-3 py-2 bg-white border-2 border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-gray-900 font-medium" placeholder="John" required />
            </div>
            <div>
                <label for="parentLastName" class="block text-base font-semibold text-gray-900 mb-2">Last Name</label>
                <input type="text" id="parentLastName" class="w-full px-3 py-2 bg-white border-2 border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-gray-900 font-medium" placeholder="Doe" required />
            </div>
            <div>
                <label for="parentPhone" class="block text-base font-semibold text-gray-900 mb-2">Phone Number</label>
                <input type="tel" id="parentPhone" class="w-full px-3 py-2 bg-white border-2 border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-gray-900 font-medium" placeholder="09171234567" required />
            </div>
            <div>
                <label for="parentEmail" class="block text-base font-semibold text-gray-900 mb-2">Email Address</label>
                <input type="email" id="parentEmail" class="w-full px-3 py-2 bg-white border-2 border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-gray-900 font-medium" placeholder="john.doe@email.com" required />
            </div>
            <div>
                <label for="parentBirthday" class="block text-base font-semibold text-gray-900 mb-2">Birthday</label>
                <input type="date" id="parentBirthday" class="w-full px-3 py-2 bg-white border-2 border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-gray-900 font-medium" required />
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('playhouse.otp') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition-colors text-center">Back</a>
                <button type="button" id="parentNext" class="flex-1 bg-teal-500 hover:bg-teal-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">Next</button>
            </div>
        </div>
    </div>
@endsection

@section('section-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nextBtn = document.getElementById('parentNext');
    const parentName = document.getElementById('parentName');
    const parentLastName = document.getElementById('parentLastName');
    const parentPhone = document.getElementById('parentPhone');
    const parentEmail = document.getElementById('parentEmail');
    const parentBirthday = document.getElementById('parentBirthday');

    nextBtn.addEventListener('click', function() {
        const name = parentName.value.trim();
        const lastName = parentLastName.value.trim();
        const phone = parentPhone.value.trim();
        const email = parentEmail.value.trim();
        const birthday = parentBirthday.value.trim();

        if (!name || !lastName || !phone || !email || !birthday) {
            alert('Please fill out all parent information fields.');
            return;
        }

        window.location.href = '{{ route("playhouse.children") }}';
    });
});
</script>
@endsection
