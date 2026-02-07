<div class="bg-teal-100 p-6">
	<h1 class="text-2xl font-bold text-gray-900 mb-2">Play Now!</h1>
	<p class="text-gray-600 mb-6">Please review your information before submitting.</p>

	<!-- Modified Preview card (visual-only changes):
		- Updated to cyan-tinted gradient for better visibility
		- Labels and separators set to stronger cyan colors
		- Children list renders cards from localStorage (unchanged behavior)
	--> 
	<!-- Preview card -->
	<div class="bg-gradient-to-br from-teal-50 to-cyan-50 border-2 border-teal-200 rounded-lg p-6 mb-6 shadow-sm">
		<div class="space-y-4">
			<div class="flex items-start border-b border-cyan-400 pb-3">
				<span class="font-semibold text-cyan-800 w-24">Phone:</span>
				<span class="text-gray-900 font-medium flex-1" id="summary-phone">-</span>
			</div>
			<div class="flex items-start border-b border-cyan-400 pb-3">
				<span class="font-semibold text-cyan-800 w-24">OTP:</span>
				<span class="text-gray-900 font-medium flex-1" id="summary-otp">-</span>
			</div>
			<div class="flex items-start border-b border-cyan-400 pb-3">
				<span class="font-semibold text-cyan-800 w-24">Parent:</span>
				<span class="text-gray-900 font-medium flex-1" id="summary-parent">-</span>
			</div>
			<div class="pb-3">
				<!-- Modified: removed bottom separator line for Children block to match visual preference -->
				<span class="font-semibold text-cyan-800 block mb-3">Children:</span>
				<div id="summary-children-list" class="space-y-3 ml-2">
					<!-- Children will be populated here -->
				</div>
			</div>
		</div>
	</div>

	<div class="flex items-center mb-6">
		<label class="inline-flex items-center text-sm text-gray-600">
			<input id="agree-terms" type="checkbox" class="form-checkbox h-4 w-4 text-green-600 bg-gray-400" />
			<span class="ml-2">I agree to the terms and conditions.</span>
		</label>
	</div>

	<div class="flex items-center justify-between">
		<a href="{{ route('playhouse.children') }}" id="previous-btn" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Previous</a>
		<button id="confirm-btn" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 disabled:opacity-60" disabled>Confirm</button>
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
			const parentLastName = localStorage.getItem('playhouse.parentLastName') || '';
			const parentEmail = localStorage.getItem('playhouse.parentEmail') || '';
			const childrenJson = localStorage.getItem('playhouse.children') || '[]';
			const duration = localStorage.getItem('playhouse.duration') || '';

			// Debug log
			console.log('Play Now page - localStorage data:', {phone, otp, parentName, parentLastName, parentEmail, childrenJson, duration});

			// Populate fields with actual user values
			setText('summary-phone', phone);
			setText('summary-otp', otp);
			
			// Construct full parent name with email
			const fullParentName = (parentName + ' ' + parentLastName).trim();
			const parentDisplay = fullParentName ? (fullParentName + (parentEmail ? ' ('+parentEmail+')' : '')) : (parentEmail || '');
			setText('summary-parent', parentDisplay);

			// Parse children array and build separated display
			try {
				const children = JSON.parse(childrenJson);
				const childrenListDiv = document.getElementById('summary-children-list');
				
				if (children && children.length > 0) {
					childrenListDiv.innerHTML = '';
					children.forEach((child, index) => {
						const childDiv = document.createElement('div');
						childDiv.className = 'bg-teal-50 border border-teal-200 rounded p-3';
						childDiv.innerHTML = `
							<p class="text-gray-900 font-semibold">${child.name || '-'}</p>
							<p class="text-sm text-gray-600 mt-1">Birthday: <span class="font-medium text-gray-900">${child.birthday || '-'}</span></p>
							<p class="text-sm text-gray-600 mt-1">Duration: <span class="font-medium text-gray-900">${child.duration || '-'}</span></p>
						`;
						childrenListDiv.appendChild(childDiv);
					});
				} else {
					childrenListDiv.innerHTML = '<p class="text-gray-500">-</p>';
				}
			} catch(e) {
				document.getElementById('summary-children-list').innerHTML = '<p class="text-gray-500">-</p>';
			}

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

