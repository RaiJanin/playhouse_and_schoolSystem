@extends('layout.app')

@section('title', 'Playhouse Registration')

@section('styles')

@endsection


@section('main-content')
    @include('components.backdrop')
    <div class="container max-w-6xl mx-auto">
        @include('ui.partials.header')
        @include('ui.partials.progress-bar', ['current' => 1])
        <div class="bg-teal-100 rounded-lg p-3 shadow-md">
            @include('ui.playhouse-first')
        </div>
    </div>
@endsection

@section('scripts')
<script>
function validateAndProceed() {
    const phoneInput = document.getElementById('phone');
    const phone = phoneInput.value.trim();
    
    // Remove spaces/dashes for validation
    const cleanPhone = phone.replace(/[\s\-\(\)]/g, '');
    
    // Philippine mobile pattern: +639XXXXXXXXX, 09XXXXXXXXX, or 9XXXXXXXXX
    const phMobilePattern = /^(?:\+63|0)?9\d{9}$/;
    
    if (!cleanPhone) {
        alert('Please enter your phone number');
        phoneInput.focus();
        return;
    }
    
    if (!phMobilePattern.test(cleanPhone)) {
        alert('Invalid Philippine mobile number!\n\nValid formats:\n• 09171234567\n• +639171234567\n• 9171234567');
        phoneInput.focus();
        return;
    }
    
    // Redirect to OTP page
    window.location.href = '{{ route("playhouse.otp") }}';
}
</script>
@endsection


@section('scripts')
    
@endsection