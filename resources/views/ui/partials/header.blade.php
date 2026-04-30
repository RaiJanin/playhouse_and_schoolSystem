<div class="sm:flex md:grid md:grid-cols-2 lg:grid-cols-3 bg-[var(--color-primary)] backdrop-blur rounded-xl mb-3 shadow-sm z-20">
    <div class="hidden md:flex overflow-hidden items-center justify-center rounded-xl">
        <img src="/images/mimo-full-logo.png" alt="MIMO Play Cafe Logo" class="h-24 object-cover object-center" onerror="this.onerror=null;this.src='/images/mimo-logo.png';" />
    </div>
    <div class="sm:hidden overflow-hidden flex items-center justify-center rounded-xl">
        <img src="/images/mimo-full-logo.png" alt="MIMO Play Cafe Logo" class="h-24 object-cover object-center" onerror="this.onerror=null;this.src='/images/mimo-logo.png';" />
    </div>
    <div class="flex flex-col justify-center items-center gap-4 sm:gap-6 p-4">
        <div class="flex flex-col items-center text-left">
            <h1 class="sm:text-3xl md:text-5xl text-2xl font-extrabold text-[var(--color-accent)] [text-shadow:2px_2px_4px_rgba(0,0,0,0.3)]">
                Mimo Play Cafe
            </h1>
            <p class="md:text-xl sm:text-base text-center text-sm text-[var(--color-accent)] [text-shadow:2px_2px_4px_rgba(0,0,0,0.3)]">
                Moments in, Memories out
            </h1>
        </div>  
    </div>
    <div class="lg:col-span-3 md:col-span-2 p-4">
        <div class="flex items-end justify-end">
            @if(Route::is('playhouse.checkout'))
                <a class="text-sm sm:text-base text-[var(--color-accent)] font-semibold underline decoration-2 underline-offset-2 decoration-white" href="{{ route('playhouse.checkin.source')}}">Go to check in</a>
            @endif
            @if(Route::is('playhouse.checkin.source') || Route::is('playhouse.registration'))
                <a class="text-sm sm:text-base text-[var(--color-accent)] font-semibold underline decoration-2 underline-offset-2 decoration-white" href="{{ route('playhouse.checkout')}}">Go to check out</a>
            @endif
        </div>
    </div>
</div>
