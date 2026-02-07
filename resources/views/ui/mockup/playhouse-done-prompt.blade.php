<div class="p-3">
	<h1 class="text-2xl font-bold text-gray-900 mb-2">Play Now!</h1>
	<p class="text-gray-600 mb-6">Please review your information before submitting.</p>

	<!-- Modified Preview card (visual-only changes):
		- Updated to cyan-tinted gradient for better visibility
		- Labels and separators set to stronger cyan colors
		- Children list renders cards from localStorage (unchanged behavior)
	--> 
	<!-- Preview card -->
	<div class="bg-gradient-to-br from-teal-50 to-cyan-50 border-2 border-teal-200 rounded-lg p-6 mb-6 shadow-sm">
		<div id="summaryContainer" class="space-y-4">
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

	<div class="flex items-center mb-2">
		<button type="button" id="agree-terms" class="cursor-pointer p-2 text-sm hover:text-gray-500">
			<span class="flex items-center">
				<i id="check-agree-terms" class="fa-solid fa-square-xmark text-red-500 text-xl"></i>
				<p class="ml-2">I agree to the <span><a target="__blank" href="https://termly.io/html_document/website-terms-and-conditions-text-format/" class="text-blue-500">terms and conditions.</a></span> </p>
			</span>
		</button>
	</div>
</div>
