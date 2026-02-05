@extends('layout.content')


@section('contents')
    <div class="step" id="step3">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Parent Information</h2>
        <p class="text-center text-gray-600 mb-6">Please provide your details</p>
        <form class="space-y-4">
            <div>
                <label for="parentName" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                <input type="text" id="parentName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="John" required />
            </div>
            <div>
                <label for="parentLastName" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                <input type="text" id="parentLastName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Doe" required />
            </div>
            <div>
                <label for="parentPhone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input type="tel" id="parentPhone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="09171234567" required />
            </div>
            <div>
                <label for="parentEmail" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" id="parentEmail" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="john.doe@email.com" required />
            </div>
            <div>
                <label for="parentBirthday" class="block text-sm font-medium text-gray-700 mb-2">Birthday</label>
                <input type="date" id="parentBirthday" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" required />
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('playhouse.phone') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition-colors text-center">Back</a>
                <button type="submit" class="flex-1 bg-teal-500 hover:bg-teal-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">Next</button>
            </div>
        </form>
    </div>
@endsection

@section('section-scripts')

@endsection
