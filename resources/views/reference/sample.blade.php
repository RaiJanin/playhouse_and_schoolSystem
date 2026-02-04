<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlayCare Registration System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('sample-assets/style.css') }}">
</head>
<body>
    <!-- Kiddie Decorations -->
    <div class="kiddie-decoration">
        <div class="balloon" style="top: 10%; left: 5%;"></div>
        <div class="star" style="top: 20%; right: 10%;"></div>
        <div class="cloud" style="top: 5%; right: 20%;"></div>
        <div class="balloon" style="bottom: 15%; right: 5%; background: radial-gradient(circle, #4dabf7, #339af0);"></div>
        <div class="star" style="bottom: 25%; left: 15%;"></div>
    </div>

    <div class="container">
        <div class="header">
            <h1>PlayCare Adventure Zone</h1>
            <p>Where Little Explorers Have Big Fun!</p>
        </div>

        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress-step active">1</div>
                <div class="progress-step">2</div>
                <div class="progress-step">3</div>
                <div class="progress-step">4</div>
                <div class="progress-step">5</div>
                <div class="progress-step">6</div>
                <div class="progress-step">7</div>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0 10px;">
                <div class="step-label">Phone</div>
                <div class="step-label">OTP</div>
                <div class="step-label">Parent</div>
                <div class="step-label">Child 1</div>
                <div class="step-label">More Kids</div>
                <div class="step-label">Hours</div>
                <div class="step-label">Done!</div>
            </div>
        </div>

        <div class="registration-container">
        <!-- Step 1: Phone Number -->
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

            <!-- Step 2: OTP Verification -->
            <div class="step" id="step2">
                <h2 class="step-title">Verify Your Phone</h2>
                <p style="text-align: center; margin-bottom: 20px;">We've sent a 3-digit code to your phone</p>
                <div class="otp-container">
                    <input type="text" class="otp-input form-control" maxlength="1" oninput="moveToNext(this, 0)">
                    <input type="text" class="otp-input form-control" maxlength="1" oninput="moveToNext(this, 1)">
                    <input type="text" class="otp-input form-control" maxlength="1" oninput="moveToNext(this, 2)">
                </div>
                <div style="text-align: center; margin: 10px 0;">
                    <button class="btn-secondary" onclick="resendOTP()">Resend Code</button>
                </div>
                <div class="btn-group">
                    <button class="btn-secondary" onclick="prevStep(2)">← Back</button>
                    <button class="btn" onclick="verifyOTP()">Verify</button>
                </div>
            </div>

            <!-- Step 3: Parent Information -->
            <div class="step" id="step3">
                <h2 class="step-title">Parent Information</h2>
                <div class="form-group">
                    <label for="parentName">First Name</label>
                    <input type="text" id="parentName" class="form-control" placeholder="John" required>
                </div>
                <div class="form-group">
                    <label for="parentLastName">Last Name</label>
                    <input type="text" id="parentLastName" class="form-control" placeholder="Doe" required>
                </div>
                <div class="form-group">
                    <label for="parentPhone">Phone Number</label>
                    <input type="tel" id="parentPhone" class="form-control" placeholder="+1 (555) 123-4567" required>
                </div>
                <div class="form-group">
                    <label for="parentEmail">Email Address</label>
                    <input type="email" id="parentEmail" class="form-control" placeholder="john.doe@email.com" required>
                </div>
                <div class="form-group">
                    <label for="parentBirthday">Birthday</label>
                    <input type="date" id="parentBirthday" class="form-control" required>
                </div>
                <div class="btn-group">
                    <button class="btn-secondary" onclick="prevStep(3)">← Back</button>
                    <button class="btn" onclick="nextStep(3)">Next →</button>
                </div>
            </div>

            <!-- Step 4: Child 1 Information -->
            <div class="step" id="step4">
                <h2 class="step-title">Child Information</h2>
                <div class="children-container" id="childrenContainer">
                    <div class="child-form">
                        <button class="remove-child" onclick="removeChild(this)" style="display: none;">✕</button>
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control child-name" placeholder="Emma" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" class="form-control child-lastname" placeholder="Smith" required>
                        </div>
                        <div class="form-group">
                            <label>Birthday</label>
                            <input type="date" class="form-control child-birthday" required>
                        </div>
                    </div>
                </div>
                <div style="text-align: center; margin: 20px 0;">
                    <button class="btn-secondary" onclick="addChild()">+ Add Another Child</button>
                </div>
                <div class="btn-group">
                    <button class="btn-secondary" onclick="prevStep(4)">← Back</button>
                    <button class="btn" onclick="nextStep(4)">Next →</button>
                </div>
            </div>

            <!-- Step 5: Hours Selection -->
            <div class="step" id="step5">
                <h2 class="step-title">⏰ Choose Play Hours</h2>
                <p style="text-align: center; margin-bottom: 20px;">Select how many hours each child will play:</p>
                <div class="hours-grid">
                    <div class="hour-option" onclick="selectHour(this, '1 hour')">1 Hour</div>
                    <div class="hour-option" onclick="selectHour(this, '2 hours')">2 Hours</div>
                    <div class="hour-option" onclick="selectHour(this, '3 hours')">3 Hours</div>
                    <div class="hour-option" onclick="selectHour(this, 'unlimited')">Unlimited</div>
                </div>
                <div class="btn-group">
                    <button class="btn-secondary" onclick="prevStep(5)">← Back</button>
                    <button class="btn" onclick="nextStep(5)">Next →</button>
                </div>
            </div>

            <!-- Step 6: Payment & Name Tag -->
            <div class="step" id="step6">
                <h2 class="step-title">Payment & Name Tags</h2>
                <div style="text-align: center; margin: 20px 0;">
                    <h3 style="color: var(--primary-color); margin-bottom: 10px;">Please proceed to cashier for payment</h3>
                    <p style="font-size: 1.1rem; margin-bottom: 20px;">Cash or Card Accepted</p>
                    
                    <div class="name-tag">
                        <div class="nickname" id="tagName">EMMA</div>
                        <div class="lastname" id="tagLastname">SMITH</div>
                        <div class="qr-code">QR</div>
                        <div class="time-info" id="tagTime">Start: 2:00 PM</div>
                        <div class="time-info" id="tagDate">Date: Jan 30, 2026</div>
                    </div>

                    <button class="btn" onclick="printNameTag()" style="margin-top: 20px;">
                        Print Name Tag
                    </button>
                </div>
                <div class="btn-group">
                    <button class="btn-secondary" onclick="prevStep(6)">← Back</button>
                    <button class="btn" onclick="completeRegistration()">Complete Registration</button>
                </div>
            </div>

            <!-- Step 7: Confirmation -->
            <div class="step" id="step7">
                <h2 class="step-title">Registration Complete!</h2>
                <div style="text-align: center; padding: 40px 20px;">
                    <div style="font-size: 4rem; margin-bottom: 20px;">🎊</div>
                    <h3 style="color: var(--primary-color); margin-bottom: 15px;">Welcome to PlayCare!</h3>
                    <p style="font-size: 1.2rem; margin-bottom: 20px;">Your children are all set for an amazing adventure!</p>
                    <p style="font-size: 1.1rem; margin-bottom: 30px; color: var(--secondary-color);">
                        Please keep your name tags and enjoy your time with us!
                    </p>
                    <button class="btn" onclick="resetForm()">Register Another Family</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('sample-assets/script.js') }}"></script>
</body>
</html>