@extends('layouts.app')

@section('content')
    <div class="relative min-h-screen bg-slate-50">
        <!-- Soft background glow -->
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-red-500/10 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-red-500/10 blur-3xl"></div>
        </div>

        <div class="relative py-8 sm:py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-600">Dashboard</p>
                        <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">
                            Welcome back, {{ auth()->user()->name }} 👋
                        </h1>
                        <p class="mt-2 max-w-2xl text-sm text-slate-600">
                            Discover matching blood requests, manage your donor status, and respond faster when someone
                            needs help.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('blood-requests.create') }}"
                            class="inline-flex items-center justify-center rounded-2xl bg-red-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700">
                            Request Blood
                        </a>

                        <a href="{{ route('profile.edit') }}"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-red-200 hover:text-red-600">
                            Edit Profile
                        </a>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-slate-500">Total Requests</p>
                        <h3 class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['total_requests'] }}</h3>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-slate-500">Active Donors</p>
                        <h3 class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['active_donors'] }}</h3>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-slate-500">My Requests</p>
                        <h3 class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['my_requests'] }}</h3>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm text-slate-500">Matched Requests</p>
                        <h3 class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['matched_requests'] }}</h3>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-3">
                    <!-- Left side -->
                    <div class="space-y-6 xl:col-span-2">
                        <!-- Best Matches -->
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold text-slate-900">Best Matches for You</h2>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Based on your blood group and district.
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6 space-y-4">
                                @forelse ($matchedRequests as $request)
                                    <div
                                        class="rounded-2xl border border-slate-200 p-4 transition hover:border-red-200 hover:bg-red-50/40">
                                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                            <div>
                                                <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                                                    <span
                                                        class="inline-flex rounded-xl bg-red-100 px-3 py-1 text-sm font-semibold text-red-700">
                                                        {{ $request->blood_group }}
                                                    </span>

                                                    @if ($request->is_emergency)
                                                        <span
                                                            class="inline-flex rounded-xl bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">
                                                            Emergency
                                                        </span>
                                                    @endif

                                                    <span class="text-sm text-slate-500">
                                                        Needed on
                                                        {{ \Carbon\Carbon::parse($request->needed_date)->format('d M Y') }}
                                                    </span>
                                                </div>

                                                <h3 class="mt-3 text-base font-semibold text-slate-900">
                                                    {{ $request->patient_name }}
                                                </h3>

                                                <p class="mt-1 text-sm text-slate-600">
                                                    {{ $request->district?->name ?? 'Unknown district' }}
                                                    @if ($request->cityCorporation?->name)
                                                        • {{ $request->cityCorporation->name }}
                                                    @endif
                                                    @if ($request->hospital_name)
                                                        • {{ $request->hospital_name }}
                                                    @endif
                                                </p>
                                            </div>

                                            <div class="flex flex-wrap gap-2">
                                                <span
                                                    class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-medium text-slate-700">
                                                    {{ $request->quantity_bags ?? 1 }} bag(s)
                                                </span>

                                                <a href="{{ route('blood-requests.show', $request->id) }}"
                                                    class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-800">
                                                    View
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                                        <p class="text-sm text-slate-500">
                                            No matching blood requests found right now.
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Recent requests -->
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold text-slate-900">Recent Blood Requests</h2>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Latest requests from across the platform.
                                    </p>
                                </div>

                                <a href="{{ route('blood-requests.index') }}"
                                    class="text-sm font-semibold text-red-600 hover:text-red-700">
                                    View all
                                </a>
                            </div>

                            <div class="mt-6 space-y-4">
                                @forelse ($recentRequests as $request)
                                    <div
                                        class="rounded-2xl border border-slate-200 p-4 transition hover:border-red-200 hover:bg-red-50/40">
                                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                            <div>
                                                <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                                                    <span
                                                        class="inline-flex rounded-xl bg-red-100 px-3 py-1 text-sm font-semibold text-red-700">
                                                        {{ $request->blood_group }}
                                                    </span>

                                                    @if ($request->is_emergency)
                                                        <span
                                                            class="inline-flex rounded-xl bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">
                                                            Emergency
                                                        </span>
                                                    @endif

                                                    <span class="text-sm text-slate-500">
                                                        Needed on
                                                        {{ \Carbon\Carbon::parse($request->needed_date)->format('d M Y') }}
                                                    </span>
                                                </div>

                                                <h3 class="mt-3 text-base font-semibold text-slate-900">
                                                    {{ $request->patient_name }}
                                                </h3>

                                                <p class="mt-1 text-sm text-slate-600">
                                                    {{ $request->district?->name ?? 'Unknown district' }}
                                                    @if ($request->cityCorporation?->name)
                                                        • {{ $request->cityCorporation->name }}
                                                    @endif
                                                    @if ($request->hospital_name)
                                                        • {{ $request->hospital_name }}
                                                    @endif
                                                </p>
                                            </div>

                                            <div class="flex flex-wrap gap-2">
                                                <span
                                                    class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-medium text-slate-700">
                                                    {{ $request->quantity_bags ?? 1 }} bag(s)
                                                </span>

                                                <a href="{{ route('blood-requests.show', $request->id) }}"
                                                    class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-800">
                                                    View
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                                        <p class="text-sm text-slate-500">No blood requests found yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Right side -->
                    <div class="space-y-6">
                        <!-- Quick Actions -->
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-slate-900">Quick Actions</h2>

                            <div class="mt-5 space-y-3">
                                <a href="{{ route('blood-requests.create') }}"
                                    class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600">
                                    <span>Create Blood Request</span>
                                    <span>→</span>
                                </a>

                                <a href="{{ route('blood-requests.index') }}"
                                    class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600">
                                    <span>Browse Requests</span>
                                    <span>→</span>
                                </a>

                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600">
                                    <span>Edit Profile</span>
                                    <span>→</span>
                                </a>

                                <a href="{{ route('blood-requests.my') }}"
                                    class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600">
                                    <span>My Requests</span>
                                    <span>→</span>
                                </a>
                            </div>
                        </div>

                        <!-- Donor Status -->
                        <div class="rounded-3xl border border-red-100 bg-gradient-to-br from-red-50 to-white p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-slate-900">Donor Status</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Keep your donor availability accurate so urgent patients can reach the right person quickly.
                            </p>

                            <div class="mt-5 rounded-2xl bg-white/80 p-4">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Current Status</p>
                                <p
                                    class="mt-2 text-base font-semibold {{ ($profile?->is_available ?? false) ? 'text-emerald-600' : 'text-slate-500' }}">
                                    {{ ($profile?->is_available ?? false) ? 'Available to Donate' : 'Currently Unavailable' }}
                                </p>

                                @if ($profile?->last_donate_date)
                                    <p class="mt-3 text-sm text-slate-500">
                                        Last donated:
                                        {{ \Carbon\Carbon::parse($profile->last_donate_date)->format('d M Y') }}
                                    </p>
                                @endif

                                @if ($profile?->next_eligible_date)
                                    <p class="mt-1 text-sm text-slate-500">
                                        Next eligible:
                                        {{ \Carbon\Carbon::parse($profile->next_eligible_date)->format('d M Y') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Profile Completion -->
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h2 class="text-lg font-semibold text-slate-900">Complete Your Profile</h2>
                                    <p class="mt-2 text-sm leading-6 text-slate-600">
                                        A complete profile improves donor matching and helps patients find you faster.
                                    </p>
                                </div>

                                <div
                                    class="rounded-2xl bg-red-50 px-3 py-2 text-sm font-semibold text-red-700 ring-1 ring-red-100">
                                    {{ $profileCompletion }}%
                                </div>
                            </div>

                            <div class="mt-5">
                                <div class="h-3 w-full overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-red-500" style="width: {{ $profileCompletion }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 space-y-3">
                                @if (!auth()->user()->phone)
                                    <div class="rounded-2xl bg-amber-50 px-4 py-3 text-sm text-amber-800">
                                        Add your phone number.
                                    </div>
                                @endif

                                @if (!auth()->user()->blood_group)
                                    <div class="rounded-2xl bg-amber-50 px-4 py-3 text-sm text-amber-800">
                                        Add your blood group.
                                    </div>
                                @endif

                                @if (!auth()->user()->division_id || !auth()->user()->district_id)
                                    <div class="rounded-2xl bg-amber-50 px-4 py-3 text-sm text-amber-800">
                                        Update your location details.
                                    </div>
                                @endif

                                @if (!auth()->user()->address_line)
                                    <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        Adding your address can make your profile more helpful.
                                    </div>
                                @endif

                                @if ($profileCompletion >= 100)
                                    <div class="rounded-2xl bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                                        Your profile looks complete and ready for better matching.
                                    </div>
                                @endif
                            </div>

                            <a href="{{ route('profile.edit') }}"
                                class="mt-5 inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-red-200 hover:text-red-600">
                                Update Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
