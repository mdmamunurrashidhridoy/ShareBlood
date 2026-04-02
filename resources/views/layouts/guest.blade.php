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
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-64 bg-gradient-to-b from-rose-100/70 via-white/40 to-transparent"></div>
        <div class="absolute -top-16 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-rose-200/30 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-72 w-72 rounded-full bg-red-100/25 blur-3xl"></div>
    </div>

    <div class="relative flex min-h-screen flex-col items-center justify-center px-4 py-10">
        <div class="mb-8">
            <a href="{{ route('landingPage') }}" class="group flex flex-col items-center gap-3 text-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-white shadow-sm ring-1 ring-rose-100 transition group-hover:shadow-md">
                    <x-application-logo class="h-9 w-9 text-red-600" />
                </div>

                <div>
                    <div class="text-lg font-semibold tracking-tight text-slate-900 transition group-hover:text-red-600">
                        {{ config('app.name', 'Blood Donation') }}
                    </div>
                    <div class="mt-1 text-sm text-slate-500">
                        A calm place to find help and save lives
                    </div>
                </div>
            </a>
        </div>

        <div class="w-full max-w-md">
            <div class="rounded-[28px] border border-white/80 bg-white/90 p-7 shadow-[0_10px_40px_rgba(15,23,42,0.06)] backdrop-blur">
                {{ $slot }}
            </div>
        </div>

        <p class="mt-8 text-center text-sm text-slate-500">
            Donate blood. Save lives. One peaceful step at a time.
        </p>
    </div>

    @stack('scripts')
</body>

</html>