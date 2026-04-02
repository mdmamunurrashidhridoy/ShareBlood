@extends('layouts.admin')

@section('content')
    <div class="relative py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100">
                        Manage Users
                    </h1>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        Search, filter, verify, block, and manage platform users.
                    </p>
                </div>

                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:border-red-200 hover:text-red-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">
                    Back to Dashboard
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

            <div class="mb-6 rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <form method="GET" action="{{ route('admin.users.index') }}" class="grid gap-4 md:grid-cols-2 xl:grid-cols-6">
                    <div class="xl:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Search</label>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Name, email, or phone"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none ring-0 placeholder:text-slate-400 focus:border-red-300 focus:ring-2 focus:ring-red-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Role</label>
                        <select name="role" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            <option value="">All</option>
                            <option value="user" @selected(request('role') === 'user')>User</option>
                            <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Blood Group</label>
                        <select name="blood_group" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            <option value="">All</option>
                            @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $group)
                                <option value="{{ $group }}" @selected(request('blood_group') === $group)>{{ $group }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Blocked</label>
                        <select name="is_blocked" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            <option value="">All</option>
                            <option value="1" @selected(request('is_blocked') === '1')>Blocked</option>
                            <option value="0" @selected(request('is_blocked') === '0')>Active</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Verified</label>
                        <select name="is_verified" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            <option value="">All</option>
                            <option value="1" @selected(request('is_verified') === '1')>Verified</option>
                            <option value="0" @selected(request('is_verified') === '0')>Not Verified</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">District</label>
                        <select name="district_id" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            <option value="">All</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" @selected((string) request('district_id') === (string) $district->id)>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Availability</label>
                        <select name="availability" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            <option value="">All</option>
                            <option value="available" @selected(request('availability') === 'available')>Available</option>
                            <option value="unavailable" @selected(request('availability') === 'unavailable')>Unavailable</option>
                            <option value="no_profile" @selected(request('availability') === 'no_profile')>No Donor Profile</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-3 xl:col-span-2">
                        <button class="inline-flex items-center rounded-2xl bg-red-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-red-700">
                            Apply Filters
                        </button>

                        <a href="{{ route('admin.users.index') }}"
                           class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:border-slate-300 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                        <thead class="bg-slate-50 dark:bg-slate-950/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">User</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Blood</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Location</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Role</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Donor</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($users as $user)
                                <tr>
                                    <td class="px-6 py-4 align-top">
                                        <div class="font-medium text-slate-900 dark:text-slate-100">{{ $user->name }}</div>
                                        <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $user->email }}</div>
                                        <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $user->phone }}</div>
                                    </td>

                                    <td class="px-6 py-4 align-top text-sm text-slate-700 dark:text-slate-300">
                                        {{ $user->blood_group ?? 'N/A' }}
                                    </td>

                                    <td class="px-6 py-4 align-top text-sm text-slate-700 dark:text-slate-300">
                                        <div>{{ $user->district?->name ?? 'N/A' }}</div>
                                        <div class="mt-1 text-slate-500 dark:text-slate-400">
                                            {{ $user->upazilla?->name ?? $user->cityArea?->name ?? $user->cityCorporation?->name ?? '—' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 align-top">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $user->role === 'admin' ? 'bg-violet-100 text-violet-700' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 align-top">
                                        <div class="flex flex-wrap gap-2">
                                            @if($user->is_blocked)
                                                <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-xs font-medium text-rose-700">
                                                    Blocked
                                                </span>
                                            @else
                                                <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                                    Active
                                                </span>
                                            @endif

                                            @if($user->is_verified)
                                                <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700">
                                                    Verified
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 align-top">
                                        @if($user->donorProfile)
                                            @if($user->donorProfile->is_available)
                                                <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                                    Available
                                                </span>
                                            @else
                                                <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700">
                                                    Unavailable
                                                </span>
                                            @endif
                                        @else
                                            <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                                No Profile
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 align-top text-right">
                                        <div class="flex flex-wrap justify-end gap-2">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                               class="rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 hover:border-red-200 hover:text-red-600 dark:border-slate-700 dark:text-slate-200">
                                                View
                                            </a>

                                            @if(!$user->isAdmin())
                                                @if(!$user->is_blocked)
                                                    <form method="POST" action="{{ route('admin.users.block', $user) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="rounded-xl bg-rose-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-rose-700">
                                                            Block
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('admin.users.unblock', $user) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="rounded-xl bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-emerald-700">
                                                            Unblock
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(!$user->is_verified)
                                                    <form method="POST" action="{{ route('admin.users.verify', $user) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="rounded-xl bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700">
                                                            Verify
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('admin.users.unverify', $user) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="rounded-xl bg-slate-700 px-3 py-1.5 text-xs font-medium text-white hover:bg-slate-800">
                                                            Unverify
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-200/70 px-6 py-4 dark:border-slate-800">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
