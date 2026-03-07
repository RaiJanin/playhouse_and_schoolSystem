<div style="text-align: center; padding: 20px; font-family: Arial, sans-serif;">
    <img src="{{ asset('images/mimo-logo.png') }}" alt="Mimo Play Cafe Logo" style="max-width: 200px; height: auto; margin-bottom: 20px;">
    
    <h1 style="color: #0e7a85;">JDEN SMS</h1>
    <h2 style="color: #333;">Email Verification</h2>

    <p>Your OTP Code is:</p>

    <h1 style="color: #0e7a85; font-size: 48px; letter-spacing: 5px;">{{ $otp }}</h1>

    <p>It is valid for 5 minutes, don't share your code with anyone, thank you.</p>
    
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666;">
        <p>Powered by Right Apps Incorporated.</p>
    </div>
</div>
