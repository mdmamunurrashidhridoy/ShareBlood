<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Blood Donation') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>[x-cloak]{ display:none !important; }</style>
</head>

<body class="min-h-screen bg-rose-50/40 text-slate-800 antialiased">
    <!-- Soft ambient background -->
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-64 bg-gradient-to-b from-rose-100/70 via-white/40 to-transparent"></div>
        <div class="absolute -top-16 -left-16 h-72 w-72 rounded-full bg-rose-200/30 blur-3xl"></div>
        <div class="absolute top-32 right-0 h-80 w-80 rounded-full bg-red-100/30 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-amber-100/20 blur-3xl"></div>
    </div>

    <div class="relative min-h-screen">
        @include('layouts.navigation')

        @isset($header)
            <header class="border-b border-white/70 bg-white/75 backdrop-blur supports-[backdrop-filter]:bg-white/65">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0">
                            {{ $header }}
                        </div>

                        @isset($headerActions)
                            <div class="shrink-0">
                                {{ $headerActions }}
                            </div>
                        @endisset
                    </div>
                </div>
            </header>
        @endisset

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>