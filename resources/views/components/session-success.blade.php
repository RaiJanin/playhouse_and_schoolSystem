@if (session('success'))
    <div role="alert" id="success-toast" class="fixed bottom-4 right-1 rounded-md border border-green-500 bg-green-50 p-4 shadow-sm">
        <div class="flex items-start gap-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="-mt-0.5 size-6 text-green-700">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>

            <div class="flex-1">
                <strong class="block leading-tight font-medium text-green-800"> Success </strong>

                <p class="mt-0.5 text-sm text-green-700">
                    {{ session('success')}}
                </p>
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
            }, 4000);
        });
    </script>
@endif
