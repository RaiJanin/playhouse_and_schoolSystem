@extends('layout.admin-welcome')

@section('admin-welcome')
    <!-- Left Side - Content -->
    <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-[var(--color-accent)] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
        <h1 class="mb-1 font-medium text-xl">Welcome to Mimo Cafe Admin Panel</h1>
        <p class="mb-2 text-[#706f6c]">Please sign in to continue to your dashboard.</p>

        <div class="flex gap-3 mt-6">
            @auth
                <a
                    href="{{ route('dashboard') }}"
                    class="inline-block px-5 py-2 bg-[#1b1b18] rounded-sm border border-black text-white text-sm leading-normal hover:bg-black"
                >
                    Go to Dashboard
                </a>
            @else
                <a
                    href="{{ route('login') }}"
                    class="inline-block px-5 py-2 bg-[#1b1b18] rounded-sm border border-black text-white text-sm leading-normal hover:bg-black"
                >
                    Log In
                </a>
            @endauth
        </div>
    </div>

    <!-- Right Side - Mimo Logo -->
    <div class="bg-[var(--color-accent)] relative lg:-ml-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden flex items-center justify-center p-8 lg:p-12">
        <img
            src="/images/mimo-logo-playhouse.png"
            alt="Mimo Cafe"
            class="w-full h-full object-contain"
        />
    </div>
@endsection
