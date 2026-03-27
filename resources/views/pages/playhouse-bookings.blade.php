@extends('layout.basic')

@section('title', 'Bookings - PlayHouse')

@section('contents')
    <header class="mb-6 grid grid-cols-1 sm:grid-cols-2">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Bookings</h1>
            <p class="text-gray-600 mt-1">View and manage all playhouse bookings</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <div class="flex-1 min-w-[150px] max-w-[200px] rounded-lg p-2 border border-white bg-[var(--color-primary-mid-dark)] backdrop-blur">
                <h1 class="text-gray-100 font-medium">In house kids</h1>
                <span class="text-white text-2xl flex flex-row items-center gap-4">
                    <i class="fa-solid fa-child-reaching"></i>
                    <p>{{ $statusMonitor['in_house_guardians'] }}</p>
                </span>
            </div>
            <div class="flex-1 min-w-[150px] max-w-[200px] rounded-lg p-2 border border-white bg-[var(--color-third-full-dark)] backdrop-blur">
                <h1 class="text-gray-100 font-medium">In house guardians</h1>
                <span class="text-white text-2xl flex flex-row items-center gap-4">
                    <i class="fa-solid fa-users-between-lines"></i>
                    <p>{{ $statusMonitor['in_house_kids'] }}</p>
                </span>
            </div>
            <div class="flex-1 min-w-[150px] max-w-[200px] rounded-lg p-2 border border-white bg-[var(--color-primary-mid-dark)] backdrop-blur">
                <h1 class="text-gray-100 font-medium">Total kids</h1>
                <span class="text-white text-2xl flex flex-row items-center gap-4">
                    <i class="fa-solid fa-children"></i>
                    <p>{{ $statusMonitor['total_kids'] }}</p>
                </span>
            </div>
            <div class="flex-1 min-w-[150px] max-w-[200px] rounded-lg p-2 border border-white bg-[var(--color-third-full-dark)] backdrop-blur">
                <h1 class="text-gray-100 font-medium">Total guardians</h1>
                <span class="text-white text-2xl flex flex-row items-center gap-4">
                    <i class="fa-solid fa-users"></i>
                    <p>{{ $statusMonitor['total_guardians'] }}</p>
                </span>
            </div>
            <div class="flex-1 min-w-[150px] max-w-[200px] rounded-lg p-2 border border-white bg-[var(--color-third-full-dark)] backdrop-blur">
                <h1 class="text-gray-100 font-medium">Today's Reservations</h1>
                <span class="text-white text-2xl flex flex-row items-center gap-4">
                    <i class="fa-solid fa-clipboard-user"></i>
                    <p>{{ $statusMonitor['today_reserves'] }}</p>
                </span>
            </div>
        </div>
    </header>
    @if(Route::is('playhouse.bookings'))
        @include('ui.bookings')
    @elseif(Route::is('playhouse.bookings-full-struct'))
        @include('ui.order-items')
    @endif
@endsection