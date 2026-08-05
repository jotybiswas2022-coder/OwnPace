<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#2e2a6b">
    <title>@yield('title', storeName().' — Own at your own pace')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('frontend.partials.os-inline-styles')
    @livewireStyles
    @stack('styles')
</head>
<body class="min-h-screen bg-paper text-ink antialiased" x-data="{ mobileNavOpen: false }">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:bg-mango focus:px-4 focus:py-2 focus:rounded-md">Skip to content</a>

    @include('frontend.partials.store-nav')

    <main id="main" class="flex-1">
        @yield('content')
    </main>

    @include('frontend.partials.store-footer')
    @include('frontend.partials.toasts')

    @livewireScripts
    @stack('scripts')
</body>
</html>
