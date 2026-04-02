@extends('layouts.admin')

@section('content')
    <div class="relative min-h-screen py-10">
        <!-- Soft background glow -->
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-red-500/10 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-red-500/10 blur-3xl"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-3 py-1 text-xs font-medium text-red-700 dark:border-red-900/40 dark:bg-red-950/40 dark:text-red-300">
                        <span class="inline-block h-2 w-2 rounded-full bg-red-500"></span>
                        Admin Control Center
                    </div>

                    <h1 class="mt-4 text-3xl font-semibold tracking-tight text-slate-900 dark:text-slate-100 sm:text-4xl">
                        Admin Dashboard
                    </h1>

                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-400">
                        Monitor platform activity, manage users, and review blood requests from one place.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.users.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-red-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-red-700">
                        Manage Users
                    </a>

                    <a href="{{ route('admin.blood-requests.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:border-red-200 hover:text-red-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">
                        Manage Requests
                    </a>
                </div>
            </div>

            <!-- Quick Action Cards -->
            <div class="mb-8 grid gap-4 md:grid-cols-2">
                <a href="{{ route('admin.users.index') }}"
                    class="group rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-red-200 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19a7 7 0 10-6 0M9 14h6m-8 5h10a2 2 0 002-2v-1a4 4 0 00-4-4H9a4 4 0 00-4 4v1a2 2 0 002 2z" />
                            </svg>
                        </div>

                        <div class="flex-1">
                            <h3
                                class="text-lg font-semibold text-slate-900 transition group-hover:text-red-600 dark:text-slate-100">
                                Manage Users
                            </h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">
                                Review accounts, verify real donors, block suspicious users, and inspect profiles.
                            </p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.blood-requests.index') }}"
                    class="group rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-red-200 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-3-3v6m9 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <div class="flex-1">
                            <h3
                                class="text-lg font-semibold text-slate-900 transition group-hover:text-red-600 dark:text-slate-100">
                                Manage Blood Requests
                            </h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-400">
                                Review active requests, update request status, and remove invalid or outdated entries.
                            </p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Stats Section -->
            <div class="mb-8">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Platform Overview</h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Core platform statistics at a glance.
                        </p>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div
                        class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Users</p>
                                <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 dark:text-slate-100">
                                    {{ $stats['total_users'] }}
                                </h2>
                            </div>
                            <div class="rounded-2xl bg-slate-100 p-3 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 20h5V4H2v16h5m10 0v-4a3 3 0 10-6 0v4m6 0H7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Verified Users</p>
                                <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 dark:text-slate-100">
                                    {{ $stats['verified_users'] }}
                                </h2>
                            </div>
                            <div class="rounded-2xl bg-blue-50 p-3 text-blue-600 dark:bg-blue-950/30 dark:text-blue-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Blocked Users</p>
                                <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 dark:text-slate-100">
                                    {{ $stats['blocked_users'] }}
                                </h2>
                            </div>
                            <div class="rounded-2xl bg-rose-50 p-3 text-rose-600 dark:bg-rose-950/30 dark:text-rose-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Available Donors</p>
                                <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 dark:text-slate-100">
                                    {{ $stats['available_donors'] }}
                                </h2>
                            </div>
                            <div
                                class="rounded-2xl bg-emerald-50 p-3 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 21c4.97-4.61 8-8.02 8-11a5 5 0 10-10 0 5 5 0 10-10 0c0 2.98 3.03 6.39 8 11h4z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Requests</p>
                                <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 dark:text-slate-100">
                                    {{ $stats['total_requests'] }}
                                </h2>
                            </div>
                            <div class="rounded-2xl bg-slate-100 p-3 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12h6m-3-3v6m9 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Pending Requests</p>
                                <h2 class="mt-3 text-3xl font-semibold tracking-tight text-amber-600">
                                    {{ $stats['pending_requests'] }}
                                </h2>
                            </div>
                            <div
                                class="rounded-2xl bg-amber-50 p-3 text-amber-600 dark:bg-amber-950/30 dark:text-amber-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Accepted Requests</p>
                                <h2 class="mt-3 text-3xl font-semibold tracking-tight text-blue-600">
                                    {{ $stats['accepted_requests'] }}
                                </h2>
                            </div>
                            <div class="rounded-2xl bg-blue-50 p-3 text-blue-600 dark:bg-blue-950/30 dark:text-blue-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Completed Requests</p>
                                <h2 class="mt-3 text-3xl font-semibold tracking-tight text-emerald-600">
                                    {{ $stats['completed_requests'] }}
                                </h2>
                            </div>
                            <div
                                class="rounded-2xl bg-emerald-50 p-3 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid gap-6 xl:grid-cols-2">
                <div
                    class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div
                        class="flex items-center justify-between border-b border-slate-200/70 px-6 py-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Recent Users</h2>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Latest registered accounts.</p>
                        </div>

                        <a href="{{ route('admin.users.index') }}"
                            class="text-sm font-medium text-red-600 hover:text-red-700">
                            View all
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($recentUsers as $user)
                            <div class="px-6 py-4 transition hover:bg-slate-50/70 dark:hover:bg-slate-800/40">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <h3 class="truncate font-medium text-slate-900 dark:text-slate-100">
                                                {{ $user->name }}
                                            </h3>

                                            @if($user->is_verified)
                                                <span
                                                    class="inline-flex rounded-full bg-blue-100 px-2 py-0.5 text-[11px] font-medium text-blue-700">
                                                    Verified
                                                </span>
                                            @endif

                                            @if($user->is_blocked)
                                                <span
                                                    class="inline-flex rounded-full bg-rose-100 px-2 py-0.5 text-[11px] font-medium text-rose-700">
                                                    Blocked
                                                </span>
                                            @endif
                                        </div>

                                        <p class="mt-1 truncate text-sm text-slate-500 dark:text-slate-400">
                                            {{ $user->email }} · {{ $user->phone }}
                                        </p>

                                        <div
                                            class="mt-2 flex flex-wrap items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                            <span class="rounded-full bg-slate-100 px-2.5 py-1 dark:bg-slate-800">
                                                {{ strtoupper($user->role) }}
                                            </span>
                                            <span class="rounded-full bg-slate-100 px-2.5 py-1 dark:bg-slate-800">
                                                Blood: {{ $user->blood_group ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="shrink-0 text-right">
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            {{ $user->created_at?->diffForHumans() }}
                                        </p>
                                        <a href="{{ route('admin.users.show', $user) }}"
                                            class="mt-2 inline-flex text-sm font-medium text-red-600 hover:text-red-700">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                                No users found.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div
                    class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div
                        class="flex items-center justify-between border-b border-slate-200/70 px-6 py-4 dark:border-slate-800">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Recent Blood Requests</h2>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Latest request activity.</p>
                        </div>

                        <a href="{{ route('admin.blood-requests.index') }}"
                            class="text-sm font-medium text-red-600 hover:text-red-700">
                            View all
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($recentRequests as $request)
                            <div class="px-6 py-4 transition hover:bg-slate-50/70 dark:hover:bg-slate-800/40">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="truncate font-medium text-slate-900 dark:text-slate-100">
                                                {{ $request->patient_name }}
                                            </h3>

                                            <span class="inline-flex rounded-full px-2 py-0.5 text-[11px] font-medium
                                                                @if($request->status === 'pending') bg-amber-100 text-amber-700
                                                                @elseif($request->status === 'accepted') bg-blue-100 text-blue-700
                                                                @elseif($request->status === 'completed') bg-emerald-100 text-emerald-700
                                                                @elseif($request->status === 'cancelled') bg-rose-100 text-rose-700
                                                                @else bg-slate-100 text-slate-700 @endif">
                                                {{ ucfirst($request->status) }}
                                            </span>

                                            @if($request->is_emergency)
                                                <span
                                                    class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-[11px] font-medium text-red-700">
                                                    Emergency
                                                </span>
                                            @endif
                                        </div>

                                        <p class="mt-1 truncate text-sm text-slate-500 dark:text-slate-400">
                                            {{ $request->blood_group }} · {{ $request->requester_name }} ·
                                            {{ $request->requester_phone }}
                                        </p>

                                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                            Needed: {{ $request->needed_date?->format('d M Y') }}
                                        </p>
                                    </div>

                                    <div class="shrink-0 text-right">
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            {{ $request->created_at?->diffForHumans() }}
                                        </p>
                                        <a href="{{ route('admin.blood-requests.show', $request) }}"
                                            class="mt-2 inline-flex text-sm font-medium text-red-600 hover:text-red-700">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                                No blood requests found.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
