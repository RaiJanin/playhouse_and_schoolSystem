<div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center p-6">
	<div class="w-full max-w-3xl">
		<div class="bg-white rounded-lg shadow-xl overflow-hidden">
			<div class="px-8 py-6">
				<!-- Stepper -->
				<nav class="mb-6">
					<ol class="flex items-center justify-between text-xs text-gray-500">
						@php
							$steps = ['Phone','OTP',"Parent's Info",'Child/Children Info','Playtime Duration','Play Now!'];
						@endphp
						@foreach($steps as $idx => $label)
							@php $number = $idx + 1; $active = $number === count($steps); @endphp
							<li class="flex-1 flex items-center">
								<div class="flex items-center space-x-3 w-full">
									<div class="flex items-center">
										<span class="inline-flex items-center justify-center h-8 w-8 rounded-full border-2 font-semibold {{ $active ? 'bg-green-50 border-green-300 text-green-600' : 'bg-white border-gray-200 text-gray-600' }}">{{ $number }}</span>
									</div>
									<div class="flex-1">
										<div class="text-sm {{ $active ? 'text-gray-800 font-semibold' : 'text-gray-500' }}">{{ $label }}</div>
									</div>
								</div>
							</li>
						@endforeach
					</ol>
				</nav>

				<h1 class="text-2xl font-bold text-gray-900 mb-2">Play Now!</h1>
				<p class="text-gray-600 mb-6">Please review your information before submitting.</p>

				<!-- Preview card -->
				<div class="bg-gray-50 border border-gray-100 rounded-md p-5 mb-4">
					<div class="space-y-3 text-sm text-gray-700">
						<div><span class="font-semibold">Phone:</span> <span id="summary-phone"></span></div>
						<div><span class="font-semibold">OTP:</span> <span id="summary-otp"></span></div>
						<div><span class="font-semibold">Parent:</span> <span id="summary-parent"></span></div>
						<div>
							<span class="font-semibold">Child:</span>
							<span id="summary-child"></span>
						</div>
						<div><span class="font-semibold">Duration:</span> <span id="summary-duration"></span></div>
					</div>
				</div>

				<div class="flex items-center mb-6">
					<label class="inline-flex items-center text-sm text-gray-600">
						<input id="agree-terms" type="checkbox" class="form-checkbox h-4 w-4 text-green-600" />
						<span class="ml-2">I agree to the terms and conditions.</span>
					</label>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
	(function(){
		function setText(id, value){
			const el = document.getElementById(id);
			if(!el) return;
			el.textContent = value ?? '';
		}

		document.addEventListener('DOMContentLoaded', function(){
			// Read directly from localStorage keys set by the form steps
			const phone = localStorage.getItem('playhouse.phone') || '';
			const otp = localStorage.getItem('playhouse.otp') || '';
			const parentName = localStorage.getItem('playhouse.parentName') || '';
			const parentEmail = localStorage.getItem('playhouse.parentEmail') || '';
			const childrenJson = localStorage.getItem('playhouse.children') || '[]';
			const duration = localStorage.getItem('playhouse.duration') || '';

			// Debug log
			console.log('Play Now page - localStorage data:', {phone, otp, parentName, parentEmail, childrenJson, duration});

			// Populate fields with actual user values
			setText('summary-phone', phone);
			setText('summary-otp', otp);
			setText('summary-parent', parentName ? (parentName + (parentEmail ? ' ('+parentEmail+')' : '')) : (parentEmail || ''));

			// Parse children array and build display string
			try {
				const children = JSON.parse(childrenJson);
				const childStrings = children.map(c => c.name || '').filter(Boolean);
				setText('summary-child', childStrings.join('; '));
			} catch(e) {
				setText('summary-child', '');
			}

			setText('summary-duration', duration);

			// checkbox -> enable confirm
			const agree = document.getElementById('agree-terms');
			const confirmBtn = document.getElementById('confirm-btn');
			if(agree && confirmBtn){
				agree.addEventListener('change', function(){ confirmBtn.disabled = !this.checked; });
			}

			// Confirm click handler
			if(confirmBtn){
				confirmBtn.addEventListener('click', function(e){
					e.preventDefault();
					confirmBtn.disabled = true;
					confirmBtn.textContent = 'Confirmed';
					// emit event with current summary data
					window.dispatchEvent(new CustomEvent('playhouse:confirmed', { detail: {
						phone: document.getElementById('summary-phone')?.textContent,
						otp: document.getElementById('summary-otp')?.textContent,
						parent: document.getElementById('summary-parent')?.textContent,
						child: document.getElementById('summary-child')?.textContent,
						duration: document.getElementById('summary-duration')?.textContent,
					}}));
				});
			}
		});
	})();
</script>

