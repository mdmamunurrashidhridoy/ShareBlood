@extends('layouts.app')

@section('content')
    @php
        $profileComplete =
            $user->blood_group &&
            $user->phone &&
            $user->division_id &&
            $user->district_id;

        $locationParts = array_filter([
            $user->cityArea?->name,
            $user->cityCorporation?->name,
            $user->upazilla?->name,
            $user->district?->name,
            $user->division?->name,
        ]);

        $locationText = count($locationParts) ? implode(', ', $locationParts) : 'Location not set';

        $isAvailable = optional($user->donorProfile)->is_available;
        $nextEligibleDate = optional($user->donorProfile)->next_eligible_date;
        $lastDonateDate = optional($user->donorProfile)->last_donate_date;
    @endphp

    <div class="relative min-h-screen bg-slate-50">
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-red-500/10 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-red-500/10 blur-3xl"></div>
        </div>

        <div class="relative py-8 sm:py-10">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900">My Profile</h1>
                    <p class="mt-2 text-sm text-slate-600">
                        View your profile details, donation activity, and blood request summary.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
                    <!-- Main -->
                    <div class="space-y-6 xl:col-span-2">
                        <!-- Profile Overview -->
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span
                                            class="inline-flex rounded-xl bg-red-100 px-3 py-1 text-sm font-semibold text-red-700">
                                            {{ $user->blood_group ?: 'Blood group not set' }}
                                        </span>

                                        @if ($user->is_verified)
                                            <span
                                                class="inline-flex rounded-xl bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                Verified
                                            </span>
                                        @endif

                                        @if ($user->is_blocked)
                                            <span
                                                class="inline-flex rounded-xl bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">
                                                Blocked
                                            </span>
                                        @endif

                                        <span
                                            class="inline-flex rounded-xl bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>

                                    <h2 class="mt-4 text-2xl font-bold tracking-tight text-slate-900">
                                        {{ $user->name }}
                                    </h2>

                                    <p class="mt-2 text-sm text-slate-600">{{ $user->email }}</p>
                                    <p class="mt-1 text-sm text-slate-600">{{ $user->phone }}</p>

                                    <div
                                        class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        {{ $locationText }}
                                    </div>
                                </div>

                                <div class="rounded-2xl bg-slate-50 px-4 py-3">
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Profile Status</p>
                                    <p
                                        class="mt-2 text-base font-semibold {{ $profileComplete ? 'text-emerald-700' : 'text-amber-700' }}">
                                        {{ $profileComplete ? 'Profile looks good' : 'Profile incomplete' }}
                                    </p>
                                </div>
                            </div>

                            @if ($user->medical_history)
                                <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Medical History</p>
                                    <p class="mt-2 text-sm leading-7 text-slate-700">
                                        {{ $user->medical_history }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Donation Stats -->
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <h2 class="text-lg font-semibold text-slate-900">Donation Summary</h2>
                                    <p class="mt-1 text-sm text-slate-600">
                                        Your donor activity and recent donation status.
                                    </p>
                                </div>

                                @if ($isAvailable)
                                    <span
                                        class="inline-flex rounded-xl bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        Available to Donate
                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-xl bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                        Not Available
                                    </span>
                                @endif
                            </div>

                            <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Responses</p>
                                    <p class="mt-2 text-2xl font-bold text-slate-900">
                                        {{ $donationStats['responses_total'] }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-emerald-50 p-4">
                                    <p class="text-xs font-medium uppercase tracking-wide text-emerald-600">Successful</p>
                                    <p class="mt-2 text-2xl font-bold text-emerald-700">{{ $donationStats['donated'] }}</p>
                                </div>

                                <div class="rounded-2xl bg-red-50 p-4">
                                    <p class="text-xs font-medium uppercase tracking-wide text-red-600">Bags Donated</p>
                                    <p class="mt-2 text-2xl font-bold text-red-700">{{ $donationStats['bags_donated'] }}</p>
                                </div>

                                <div class="rounded-2xl bg-sky-50 p-4">
                                    <p class="text-xs font-medium uppercase tracking-wide text-sky-600">Selected</p>
                                    <p class="mt-2 text-2xl font-bold text-sky-700">{{ $donationStats['selected'] }}</p>
                                </div>
                            </div>

                            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Last Donation Date
                                    </p>
                                    <p class="mt-2 text-base font-semibold text-slate-900">
                                        {{ $lastDonateDate ? \Carbon\Carbon::parse($lastDonateDate)->format('d M Y') : 'Not recorded' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Next Eligible Date
                                    </p>
                                    <p class="mt-2 text-base font-semibold text-slate-900">
                                        {{ $nextEligibleDate ? \Carbon\Carbon::parse($nextEligibleDate)->format('d M Y') : 'Not set' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Request Stats -->
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-slate-900">Request Summary</h2>
                            <p class="mt-1 text-sm text-slate-600">
                                Overview of all blood requests you created.
                            </p>

                            <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Total</p>
                                    <p class="mt-2 text-xl font-bold text-slate-900">{{ $requestStats['total'] }}</p>
                                </div>

                                <div class="rounded-2xl bg-amber-50 p-4">
                                    <p class="text-xs font-medium uppercase tracking-wide text-amber-600">Pending</p>
                                    <p class="mt-2 text-xl font-bold text-amber-700">{{ $requestStats['pending'] }}</p>
                                </div>

                                <div class="rounded-2xl bg-sky-50 p-4">
                                    <p class="text-xs font-medium uppercase tracking-wide text-sky-600">Accepted</p>
                                    <p class="mt-2 text-xl font-bold text-sky-700">{{ $requestStats['accepted'] }}</p>
                                </div>

                                <div class="rounded-2xl bg-emerald-50 p-4">
                                    <p class="text-xs font-medium uppercase tracking-wide text-emerald-600">Completed</p>
                                    <p class="mt-2 text-xl font-bold text-emerald-700">{{ $requestStats['completed'] }}</p>
                                </div>

                                <div class="rounded-2xl bg-slate-100 p-4">
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-600">Cancelled</p>
                                    <p class="mt-2 text-xl font-bold text-slate-700">{{ $requestStats['cancelled'] }}</p>
                                </div>

                                <div class="rounded-2xl bg-rose-50 p-4">
                                    <p class="text-xs font-medium uppercase tracking-wide text-rose-600">Expired</p>
                                    <p class="mt-2 text-xl font-bold text-rose-700">{{ $requestStats['expired'] }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Requests -->
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-slate-900">Recent Requests</h2>

                            <div class="mt-5 space-y-4">
                                @forelse ($recentRequests as $bloodRequest)
                                    <div class="rounded-2xl border border-slate-200 p-4">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <h3 class="text-base font-semibold text-slate-900">
                                                    Blood needed for {{ $bloodRequest->patient_name }}
                                                </h3>
                                                <p class="mt-1 text-sm text-slate-600">
                                                    {{ $bloodRequest->blood_group }} • Needed on
                                                    {{ $bloodRequest->needed_date->format('d M Y') }}
                                                </p>
                                            </div>

                                            <div class="flex items-center gap-3">
                                                <span
                                                    class="inline-flex rounded-xl bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                                    {{ ucfirst($bloodRequest->status) }}
                                                </span>

                                                <a href="{{ route('blood-requests.show', $bloodRequest) }}"
                                                    class="text-sm font-medium text-red-600 hover:text-red-700">
                                                    View
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div
                                        class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center">
                                        <p class="text-sm font-medium text-slate-700">No requests created yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Recent Donation Activity -->
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-slate-900">Recent Donation Activity</h2>

                            <div class="mt-5 space-y-4">
                                @forelse ($recentDonationActivities as $activity)
                                    <div class="rounded-2xl border border-slate-200 p-4">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <h3 class="text-base font-semibold text-slate-900">
                                                    {{ $activity->bloodRequest?->patient_name ? 'Request for ' . $activity->bloodRequest->patient_name : 'Donation activity' }}
                                                </h3>

                                                <p class="mt-1 text-sm text-slate-600">
                                                    Status: {{ ucfirst($activity->status) }}
                                                    @if ($activity->bloodRequest?->blood_group)
                                                        • {{ $activity->bloodRequest->blood_group }}
                                                    @endif
                                                </p>
                                            </div>

                                            <div class="flex items-center gap-3">
                                                <span
                                                    class="inline-flex rounded-xl bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                                    {{ ucfirst($activity->status) }}
                                                </span>

                                                @if ($activity->bloodRequest)
                                                    <a href="{{ route('blood-requests.show', $activity->bloodRequest) }}"
                                                        class="text-sm font-medium text-red-600 hover:text-red-700">
                                                        View
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div
                                        class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center">
                                        <p class="text-sm font-medium text-slate-700">No donation activity yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-slate-900">Quick Info</h2>

                            <div class="mt-5 space-y-4">
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">User Verified</p>
                                    <p
                                        class="mt-2 text-base font-semibold {{ $user->is_verified ? 'text-emerald-700' : 'text-amber-700' }}">
                                        {{ $user->is_verified ? 'Yes' : 'No' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Account Created
                                    </p>
                                    <p class="mt-2 text-base font-semibold text-slate-900">
                                        {{ $user->created_at->format('d M Y') }}
                                    </p>
                                </div>


                            </div>
                        </div>

                        <div class="rounded-3xl border border-red-100 bg-gradient-to-br from-red-50 to-white p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-slate-900">Donation Status</h2>

                            <div class="mt-5 space-y-3 rounded-2xl bg-white/80 p-4">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-500">Availability</span>
                                    <span class="font-semibold text-slate-900">
                                        {{ $isAvailable ? 'Available' : 'Unavailable' }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-500">Successful Donations</span>
                                    <span class="font-semibold text-slate-900">
                                        {{ $donationStats['donated'] }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-500">Pending Interest</span>
                                    <span class="font-semibold text-slate-900">
                                        {{ $donationStats['interested'] }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-500">Rejected Responses</span>
                                    <span class="font-semibold text-slate-900">
                                        {{ $donationStats['rejected'] }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-slate-900">Profile Completion Tips</h2>

                            <div class="mt-5 space-y-3 text-sm text-slate-600">
                                @if (!$user->blood_group)
                                    <div class="rounded-2xl bg-amber-50 px-4 py-3 text-amber-800">
                                        Add your blood group to get better matching.
                                    </div>
                                @endif

                                @if (!$user->division_id || !$user->district_id)
                                    <div class="rounded-2xl bg-amber-50 px-4 py-3 text-amber-800">
                                        Add your location to improve nearby donor/request matching.
                                    </div>
                                @endif

                                @if (!$user->donorProfile)
                                    <div class="rounded-2xl bg-sky-50 px-4 py-3 text-sky-800">
                                        Create your donor profile to manage availability and donation dates.
                                    </div>
                                @endif

                                @if ($profileComplete && $user->donorProfile)
                                    <div class="rounded-2xl bg-emerald-50 px-4 py-3 text-emerald-800">
                                        Your profile is in good shape.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
