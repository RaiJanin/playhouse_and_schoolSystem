@if ($errors->any())
    <div role="alert" id="success-toast" class="fixed top-4 left-1 rounded-md border border-red-500 bg-red-50 p-4 shadow-sm">
        <div class="flex items-start gap-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor"
                class="-mt-0.5 size-6 text-red-700">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zM12 16.5h.008v.008H12V16.5z" />
            </svg>

            <div class="flex-1">
                <strong class="block leading-tight font-medium text-red-700"> Error </strong>

                <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                const toast = document.getElementById('success-toast');
                if (toast) {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 500);
                }
            }, 6000);
        });
    </script>
@endif