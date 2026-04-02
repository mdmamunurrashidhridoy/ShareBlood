@extends('layouts.admin')

@section('content')
    <div class="relative py-10">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

            <div class="mb-8 flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100">
                        User Details
                    </h1>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        Manage account status, donor profile, and recent activity.
                    </p>
                </div>

                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:border-red-200 hover:text-red-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">
                    Back to Users
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-6">
                    <div
                        class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Basic Information</h2>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Name</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $user->name }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Email</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $user->email }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Phone</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $user->phone }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Blood Group</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $user->blood_group ?? 'N/A' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Role</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ ucfirst($user->role) }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Joined</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">
                                    {{ $user->created_at?->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>

                        @if($user->medical_history)
                            <div class="mt-6">
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Medical History</p>
                                <p
                                    class="mt-2 rounded-2xl bg-slate-50 p-4 text-sm text-slate-700 dark:bg-slate-950 dark:text-slate-300">
                                    {{ $user->medical_history }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <div
                        class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Location</h2>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Division</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">
                                    {{ $user->division?->name ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">District</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">
                                    {{ $user->district?->name ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Upazilla</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">
                                    {{ $user->upazilla?->name ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">City Corporation</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">
                                    {{ $user->cityCorporation?->name ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">City Area</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">
                                    {{ $user->cityArea?->name ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Address Line</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">
                                    {{ $user->address_line ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Recent Blood Requests</h2>

                        <div class="mt-4 space-y-3">
                            @forelse($recentRequests as $request)
                                <div class="rounded-2xl border border-slate-200/70 p-4 dark:border-slate-800">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="font-medium text-slate-900 dark:text-slate-100">
                                                {{ $request->patient_name }}</p>
                                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                                {{ $request->blood_group }} · Needed
                                                {{ $request->needed_date?->format('d M Y') }}
                                            </p>
                                        </div>

                                        <a href="{{ route('admin.blood-requests.show', $request) }}"
                                            class="text-sm font-medium text-red-600 hover:text-red-700">
                                            View
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500 dark:text-slate-400">No blood requests created by this user.
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div
                        class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Status</h2>

                        <div class="mt-4 flex flex-wrap gap-2">
                            @if($user->is_blocked)
                                <span
                                    class="inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-xs font-medium text-rose-700">
                                    Blocked
                                </span>
                            @else
                                <span
                                    class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                    Active
                                </span>
                            @endif

                            @if($user->is_verified)
                                <span
                                    class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700">
                                    Verified
                                </span>
                            @endif

                            <span
                                class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>

                        @if($user->donorProfile)
                            <div class="mt-6">
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Donor Availability</p>
                                <p class="mt-2 text-sm text-slate-900 dark:text-slate-100">
                                    {{ $user->donorProfile->is_available ? 'Available' : 'Unavailable' }}
                                </p>
                            </div>

                            <div class="mt-4">
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Last Donate Date</p>
                                <p class="mt-2 text-sm text-slate-900 dark:text-slate-100">
                                    {{ $user->donorProfile->last_donate_date?->format('d M Y') ?? 'N/A' }}
                                </p>
                            </div>

                            <div class="mt-4">
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Next Eligible Date</p>
                                <p class="mt-2 text-sm text-slate-900 dark:text-slate-100">
                                    {{ $user->donorProfile->next_eligible_date?->format('d M Y') ?? 'N/A' }}
                                </p>
                            </div>

                            @if($user->donorProfile->note)
                                <div class="mt-4">
                                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Donor Note</p>
                                    <p
                                        class="mt-2 rounded-2xl bg-slate-50 p-4 text-sm text-slate-700 dark:bg-slate-950 dark:text-slate-300">
                                        {{ $user->donorProfile->note }}
                                    </p>
                                </div>
                            @endif
                        @endif
                    </div>

                    @if(!$user->isAdmin())
                        <div
                            class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Actions</h2>

                            <div class="mt-4 space-y-3">
                                @if(!$user->is_blocked)
                                    <form method="POST" action="{{ route('admin.users.block', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            class="w-full rounded-2xl bg-rose-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-rose-700">
                                            Block User
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.users.unblock', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            class="w-full rounded-2xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700">
                                            Unblock User
                                        </button>
                                    </form>
                                @endif

                                @if(!$user->is_verified)
                                    <form method="POST" action="{{ route('admin.users.verify', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            class="w-full rounded-2xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                                            Verify User
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.users.unverify', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            class="w-full rounded-2xl bg-slate-700 px-4 py-2.5 text-sm font-medium text-white hover:bg-slate-800">
                                            Remove Verification
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="w-full rounded-2xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-medium text-rose-700 hover:bg-rose-100">
                                        Delete User
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
