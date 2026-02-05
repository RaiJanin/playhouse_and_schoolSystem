@extends('layout.content')


@section('contents')
    <div class="step" id="step5">
        <div class="max-w-md mx-auto bg-white rounded-lg p-6 shadow-lg">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-4" style="text-shadow: 0 1px 6px rgba(16,24,32,0.06);">Playtime Duration</h2>
            <p class="text-center text-gray-800 text-lg mb-6">Select how long your child will play</p>

            <form id="durationForm" class="space-y-4">
                <div class="space-y-3">
                    <label class="flex items-center p-4 bg-gray-50 border-2 border-gray-400 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                        <input type="radio" name="duration" value="1-hour" class="w-4 h-4 text-teal-500 focus:ring-2 focus:ring-teal-500" />
                        <span class="ml-3 text-base font-medium text-gray-900">1 Hour</span>
                        <span class="ml-auto text-sm text-gray-600">₱500</span>
                    </label>
                    <label class="flex items-center p-4 bg-gray-50 border-2 border-gray-400 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                        <input type="radio" name="duration" value="2-hours" class="w-4 h-4 text-teal-500 focus:ring-2 focus:ring-teal-500" />
                        <span class="ml-3 text-base font-medium text-gray-900">2 Hours</span>
                        <span class="ml-auto text-sm text-gray-600">₱850</span>
                    </label>
                    <label class="flex items-center p-4 bg-gray-50 border-2 border-gray-400 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                        <input type="radio" name="duration" value="3-hours" class="w-4 h-4 text-teal-500 focus:ring-2 focus:ring-teal-500" />
                        <span class="ml-3 text-base font-medium text-gray-900">3 Hours</span>
                        <span class="ml-auto text-sm text-gray-600">₱1,150</span>
                    </label>
                    <label class="flex items-center p-4 bg-gray-50 border-2 border-gray-400 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                        <input type="radio" name="duration" value="unlimited" class="w-4 h-4 text-teal-500 focus:ring-2 focus:ring-teal-500" />
                        <span class="ml-3 text-base font-medium text-gray-900">Unlimited</span>
                        <span class="ml-auto text-sm text-gray-600">₱1,500</span>
                    </label>
                </div>

                <div class="flex space-x-4 mt-6">
                    <a href="{{ route('playhouse.children') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition-colors text-center">Back</a>
                    <button type="button" id="durationNext" class="flex-1 bg-teal-500 hover:bg-teal-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">Next</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('section-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nextBtn = document.getElementById('durationNext');
    const durationForm = document.getElementById('durationForm');

    nextBtn.addEventListener('click', function() {
        const selected = document.querySelector('input[name="duration"]:checked');
        
        if (!selected) {
            alert('Please select a playtime duration.');
            return;
        }

        window.location.href = '{{ route("playhouse.done") }}';
    });
});
</script>
@endsection
