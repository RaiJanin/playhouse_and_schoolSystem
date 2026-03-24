<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
    @yield('styles')
</head>
<body class="min-h-screen px-5 py-10 bg-gradient-to-r from-[var(--color-accent-secondary-light)] to-[var(--color-accent-secondary)]">
    @include('components.backdrop')
    <main class="max-w-full mx-auto overflow-auto backdrop-blur-sm">
        @yield('contents')
    </main>
    @yield('scripts')
</body>
</html>