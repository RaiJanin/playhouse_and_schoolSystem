<div class="p-3">
	<h1 class="text-2xl font-bold text-gray-900 mb-2">Play Now!</h1>
	<p class="text-gray-600 mb-6">Please review your information before submitting.</p>

	<div class="bg-gradient-to-br from-teal-50 to-cyan-50 border-2 border-teal-200 rounded-lg p-6 mb-6 shadow-sm">
		<div id="summaryContainer" class="space-y-4">
			<!-- Summary items will be loaded here -->
		</div>
	</div>

	<div class="flex items-center mb-2">
		{{-- <button type="button" id="agree-terms" class="cursor-pointer p-2 text-sm hover:text-gray-500">
			<span class="flex items-center">
				<i id="check-agree-terms" class="fa-solid fa-square-xmark text-red-500 text-xl"></i>
				<p class="ml-2">I agree to the <span><a target="__blank" href="https://termly.io/html_document/website-terms-and-conditions-text-format/" class="text-blue-500">terms and conditions.</a></span> </p>
			</span>
		</button> --}}
		@include('components.checkbox')
	</div>
</div>
