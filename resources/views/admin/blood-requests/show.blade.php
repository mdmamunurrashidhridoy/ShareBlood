@extends('layouts.admin')

@section('content')
    <div class="relative py-10">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

            <div class="mb-8 flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100">
                        Blood Request Details
                    </h1>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        Review request information and update request status.
                    </p>
                </div>

                <a href="{{ route('admin.blood-requests.index') }}"
                   class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:border-red-200 hover:text-red-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">
                    Back to Requests
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-6">
                    <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Request Information</h2>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Patient Name</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $bloodRequest->patient_name }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Blood Group</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $bloodRequest->blood_group }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Quantity</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $bloodRequest->quantity_bags ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Needed Date</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $bloodRequest->needed_date?->format('d M Y') }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Hospital Name</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $bloodRequest->hospital_name ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Emergency</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $bloodRequest->is_emergency ? 'Yes' : 'No' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Status</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ ucfirst($bloodRequest->status) }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Expires At</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">
                                    {{ $bloodRequest->expires_at?->format('d M Y, h:i A') ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        @if($bloodRequest->note)
                            <div class="mt-6">
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Note</p>
                                <p class="mt-2 rounded-2xl bg-slate-50 p-4 text-sm text-slate-700 dark:bg-slate-950 dark:text-slate-300">
                                    {{ $bloodRequest->note }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Requester & Location</h2>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Requester Name</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $bloodRequest->requester_name }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Requester Phone</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $bloodRequest->requester_phone }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Division</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $bloodRequest->division?->name ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">District</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $bloodRequest->district?->name ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Upazilla</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $bloodRequest->upazilla?->name ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">City Corporation</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $bloodRequest->cityCorporation?->name ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">City Area</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $bloodRequest->cityArea?->name ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Address Line</p>
                                <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $bloodRequest->address_line ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Request Actions</h2>

                        <form method="POST" action="{{ route('admin.blood-requests.update-status', $bloodRequest) }}" class="mt-4 space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Update Status</label>
                                <select name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                    @foreach(\App\Models\BloodRequest::STATUSES as $status)
                                        <option value="{{ $status }}" @selected($bloodRequest->status === $status)>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button class="w-full rounded-2xl bg-red-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-700">
                                Save Status
                            </button>
                        </form>

                        <form method="POST"
                              action="{{ route('admin.blood-requests.destroy', $bloodRequest) }}"
                              class="mt-4"
                              onsubmit="return confirm('Are you sure you want to delete this blood request?')">
                            @csrf
                            @method('DELETE')

                            <button class="w-full rounded-2xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-medium text-rose-700 hover:bg-rose-100">
                                Delete Request
                            </button>
                        </form>
                    </div>

                    @if($bloodRequest->requester)
                        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Linked User</h2>

                            <div class="mt-4">
                                <p class="font-medium text-slate-900 dark:text-slate-100">{{ $bloodRequest->requester->name }}</p>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $bloodRequest->requester->email }}</p>
                            </div>

                            <a href="{{ route('admin.users.show', $bloodRequest->requester) }}"
                               class="mt-4 inline-flex items-center text-sm font-medium text-red-600 hover:text-red-700">
                                View User
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
