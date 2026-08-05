<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#2e2a6b">
    <title>{{ storeName() }} — @yield('title', 'Account')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.os-inline-styles')
    @livewireStyles
    @stack('styles')
</head>
<body class="min-h-screen bg-paper text-ink antialiased">
    <div class="flex min-h-screen flex-col">
        <header class="border-b border-ink/10 bg-white/80 backdrop-blur-md">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3.5 sm:px-6">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5" aria-label="{{ storeName() }} home">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-mango text-ink">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" opacity="0.45"/>
                            <circle cx="12" cy="12" r="4.5" fill="currentColor"/>
                        </svg>
                    </span>
                    <span class="font-display text-lg font-bold tracking-tight text-ink">{{ storeName() }}</span>
                </a>
                <a href="{{ url('/') }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-house-fill"></i> Back to home</a>
            </div>
        </header>

        <main class="relative flex flex-1 items-center justify-center overflow-hidden px-4 py-12">
            <div class="pointer-events-none absolute inset-0" aria-hidden="true">
                <div class="absolute -top-24 right-0 h-96 w-96 rounded-full bg-mango/15 blur-3xl"></div>
                <div class="absolute bottom-0 left-1/4 h-72 w-72 rounded-full bg-indigo/10 blur-3xl"></div>
            </div>
            <div class="relative w-full max-w-md">
                @yield('content')
            </div>
        </main>

        <footer class="border-t border-ink/10 bg-white py-5 text-center">
            <p class="text-xs text-slate">&copy; {{ date('Y') }} {{ storeName() }} — Own at your own pace.</p>
        </footer>
    </div>

    @include('frontend.partials.toasts')

    @livewireScripts
    @stack('scripts')
</body>
</html>
