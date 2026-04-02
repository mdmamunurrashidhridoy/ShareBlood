@extends('layouts.admin')

@section('content')
    <div class="relative py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100">
                        Manage Blood Requests
                    </h1>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        Review, filter, update status, and moderate emergency requests.
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

            <div class="mb-6 rounded-3xl border border-slate-200/70 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <form method="GET" action="{{ route('admin.blood-requests.index') }}" class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                    <div class="xl:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Search</label>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Patient, requester, phone, hospital"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-red-300 focus:ring-2 focus:ring-red-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                        >
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
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Status</label>
                        <select name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            <option value="">All</option>
                            @foreach(\App\Models\BloodRequest::STATUSES as $status)
                                <option value="{{ $status }}" @selected(request('status') === $status)>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Emergency</label>
                        <select name="is_emergency" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            <option value="">All</option>
                            <option value="1" @selected(request('is_emergency') === '1')>Emergency</option>
                            <option value="0" @selected(request('is_emergency') === '0')>Normal</option>
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

                    <div class="flex items-end gap-3 xl:col-span-2">
                        <button class="inline-flex items-center rounded-2xl bg-red-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-red-700">
                            Apply Filters
                        </button>

                        <a href="{{ route('admin.blood-requests.index') }}"
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
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Patient</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Requester</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Blood</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Needed Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Location</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($bloodRequests as $request)
                                <tr>
                                    <td class="px-6 py-4 align-top">
                                        <div class="font-medium text-slate-900 dark:text-slate-100">{{ $request->patient_name }}</div>
                                        <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                            {{ $request->hospital_name ?? 'No hospital name' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 align-top">
                                        <div class="text-sm text-slate-900 dark:text-slate-100">{{ $request->requester_name }}</div>
                                        <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $request->requester_phone }}</div>
                                    </td>

                                    <td class="px-6 py-4 align-top text-sm text-slate-700 dark:text-slate-300">
                                        <div>{{ $request->blood_group }}</div>
                                        <div class="mt-1 text-slate-500 dark:text-slate-400">
                                            {{ $request->quantity_bags ? $request->quantity_bags . ' bag(s)' : 'N/A' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 align-top text-sm text-slate-700 dark:text-slate-300">
                                        {{ $request->needed_date?->format('d M Y') }}
                                    </td>

                                    <td class="px-6 py-4 align-top text-sm text-slate-700 dark:text-slate-300">
                                        <div>{{ $request->district?->name ?? 'N/A' }}</div>
                                        <div class="mt-1 text-slate-500 dark:text-slate-400">
                                            {{ $request->upazilla?->name ?? $request->cityArea?->name ?? $request->cityCorporation?->name ?? '—' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 align-top">
                                        <div class="flex flex-wrap gap-2">
                                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium
                                                @if($request->status === 'pending') bg-amber-100 text-amber-700
                                                @elseif($request->status === 'accepted') bg-blue-100 text-blue-700
                                                @elseif($request->status === 'completed') bg-emerald-100 text-emerald-700
                                                @elseif($request->status === 'cancelled') bg-rose-100 text-rose-700
                                                @else bg-slate-100 text-slate-700 @endif">
                                                {{ ucfirst($request->status) }}
                                            </span>

                                            @if($request->is_emergency)
                                                <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700">
                                                    Emergency
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 align-top text-right">
                                        <a href="{{ route('admin.blood-requests.show', $request) }}"
                                           class="rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 hover:border-red-200 hover:text-red-600 dark:border-slate-700 dark:text-slate-200">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                                        No blood requests found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-200/70 px-6 py-4 dark:border-slate-800">
                    {{ $bloodRequests->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
