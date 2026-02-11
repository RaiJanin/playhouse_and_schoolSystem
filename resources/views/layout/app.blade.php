<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    @yield('styles')
</head>
<body class="min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <div class="flex-1 flex flex-col overflow-hidden">
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                @yield('main-content')
            </main>
        </div>
    </div>
    @yield('scripts')
</body>
</html>