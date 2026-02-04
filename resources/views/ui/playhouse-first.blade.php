@extends('layout.content')


@section('contents')
    <div class="step active" id="step1">
        <h2 class="step-title"> Enter Your Phone Number</h2>
        <p style="text-align: center; color: var(--secondary-color); margin-bottom: 20px; font-size: 0.9rem;">
            We'll send a verification code to your number
        </p>
        <div class="form-group">
            <label for="phone">Phone Number <span style="color: #ff4444;">*</span></label>
                <input 
                    type="tel" 
                    id="phone" 
                    class="form-control" 
                    placeholder="0917 123 4567" 
                    pattern="^(?:\+63|0)?9\d{9}$"
                    title="Philippine mobile number (e.g., 09171234567 or +639171234567)"
                    required
                    maxlength="16"
                >
                <small style="color: #666; font-size: 0.85rem; display: block; margin-top: 5px;">
                    Accepts: 09XXXXXXXXX, +639XXXXXXXXX, or 9XXXXXXXXX
                </small>
            </div>
        <div class="btn-group">
            <button class="btn" onclick="nextStep(1)">Next →</button>
        </div>
    </div>
@endsection