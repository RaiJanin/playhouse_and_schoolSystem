<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/mimo_logo_steady.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
    @if(Route::is('playhouse.registration'))
        @vite('resources/js/app.js')
    @endif
    @vite('resources/css/app.css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    @yield('styles')
    @yield('masterFile')
</head>
<body class="min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('components.modal')
            <main class="flex-1 overflow-y-auto bg-[var(--color-accent)] p-3 sm:p-6">
                @yield('main-content')
            </main>
        </div>
    </div>
    @yield('scripts')
</body>
</html>