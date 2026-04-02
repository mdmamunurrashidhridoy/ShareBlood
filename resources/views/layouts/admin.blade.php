<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('page_title', 'Admin Panel') - {{ config('app.name', 'Blood Donation') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-slate-50 text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">
    <div class="min-h-screen">
        <!-- Soft background glow -->
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-red-500/10 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-red-500/10 blur-3xl"></div>
        </div>

        <div x-data="{ sidebarOpen: false }" class="relative min-h-screen lg:flex">
            <!-- Mobile sidebar backdrop -->
            <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-950/40 lg:hidden"
                @click="sidebarOpen = false"></div>

            <!-- Sidebar -->
            <aside
                class="fixed inset-y-0 left-0 z-50 flex w-72 max-w-[85vw] -translate-x-full flex-col border-r border-slate-200/70 bg-white/95 backdrop-blur transition-transform duration-300 dark:border-slate-800 dark:bg-slate-900/95 lg:static lg:z-auto lg:translate-x-0"
                :class="sidebarOpen ? 'translate-x-0' : ''">
                <div
                    class="flex h-20 items-center justify-between border-b border-slate-200/70 px-6 dark:border-slate-800">
                    <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3">
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-2xl bg-red-600 text-lg text-white shadow-sm shadow-red-200/60 transition group-hover:scale-[1.02]">
                            🩸
                        </div>

                        <div class="leading-tight">
                            <div
                                class="text-sm font-semibold tracking-tight text-slate-900 transition group-hover:text-red-600 dark:text-slate-100">
                                {{ config('app.name', 'Blood Donation') }}
                            </div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">
                                Admin Panel
                            </div>
                        </div>
                    </a>

                    <button type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800 lg:hidden"
                        @click="sidebarOpen = false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto px-4 py-6">
                    <div class="mb-6 px-2">
                        <div
                            class="rounded-3xl border border-red-100 bg-red-50/80 p-4 dark:border-red-900/30 dark:bg-red-950/20">
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-red-600 dark:text-red-300">
                                Logged in as
                            </p>
                            <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-slate-100">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                    </div>

                    <nav class="space-y-2">
                        <a href="{{ route('admin.dashboard') }}"
                            class="{{ request()->routeIs('admin.dashboard') ? 'bg-red-600 text-white shadow-sm shadow-red-200/60 dark:shadow-none' : 'text-slate-700 hover:bg-slate-100 hover:text-red-600 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-red-400' }} flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 13h8V3H3v10zm10 8h8V3h-8v18zM3 21h8v-6H3v6z" />
                            </svg>
                            Dashboard
                        </a>

                        <a href="{{ route('admin.users.index') }}"
                            class="{{ request()->routeIs('admin.users.*') ? 'bg-red-600 text-white shadow-sm shadow-red-200/60 dark:shadow-none' : 'text-slate-700 hover:bg-slate-100 hover:text-red-600 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-red-400' }} flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 20h5V4H2v16h5m10 0v-4a3 3 0 10-6 0v4m6 0H7" />
                            </svg>
                            Users
                        </a>

                        <a href="{{ route('admin.blood-requests.index') }}"
                            class="{{ request()->routeIs('admin.blood-requests.*') ? 'bg-red-600 text-white shadow-sm shadow-red-200/60 dark:shadow-none' : 'text-slate-700 hover:bg-slate-100 hover:text-red-600 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-red-400' }} flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-3-3v6m9 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Blood Requests
                        </a>
                    </nav>

                    <div class="mt-8 border-t border-slate-200/70 pt-6 dark:border-slate-800">
                        <p class="px-2 text-xs font-medium uppercase tracking-[0.18em] text-slate-400">
                            Quick Access
                        </p>

                        <div class="mt-3 space-y-2">
                            <a href="{{ route('dashboard') }}"
                                class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-100 hover:text-red-600 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-red-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 12l8-8 8 8M6 10v10h12V10" />
                                </svg>
                                Back to App
                            </a>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-200/70 p-4 dark:border-slate-800">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-rose-900/30 dark:hover:bg-rose-950/20 dark:hover:text-rose-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-7.5a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 006 21h7.5a2.25 2.25 0 002.25-2.25V15m-3-3h9m0 0l-3-3m3 3l-3 3" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            <div class="flex min-w-0 flex-1 flex-col">
                <header
                    class="sticky top-0 z-30 border-b border-slate-200/70 bg-white/80 backdrop-blur supports-[backdrop-filter]:bg-white/70 dark:border-slate-800 dark:bg-slate-950/80 dark:supports-[backdrop-filter]:bg-slate-950/70">
                    <div class="flex h-20 items-center justify-between px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-3">
                            <button type="button"
                                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800 lg:hidden"
                                @click="sidebarOpen = true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                            </button>

                            <div>
                                <h1 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-slate-100">
                                    @yield('page_title', 'Admin Panel')
                                </h1>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    @yield('page_description', 'Manage platform users and blood requests.')
                                </p>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                    @if(session('success'))
                        <div
                            class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-900/30 dark:bg-emerald-950/20 dark:text-emerald-300">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div
                            class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-900/30 dark:bg-rose-950/20 dark:text-rose-300">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div
                            class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-900/30 dark:bg-rose-950/20 dark:text-rose-300">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
