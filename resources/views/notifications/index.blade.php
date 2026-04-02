@extends('layouts.app')

@section('content')
    <div class="relative min-h-screen bg-slate-50">
        <!-- Soft background glow -->
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-red-500/10 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-red-500/10 blur-3xl"></div>
        </div>

        <div class="relative py-8 sm:py-10">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <!-- Header -->
                    <div
                        class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-medium text-red-600">Activity</p>
                            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">
                                Notifications
                            </h1>
                            <p class="mt-1 text-sm text-slate-500">
                                Stay updated with blood request alerts and important activity.
                            </p>
                        </div>

                        @if(auth()->user()->unreadNotifications->count())
                            <form method="POST" action="{{ route('notifications.readAll') }}">
                                @csrf
                                <button
                                    class="inline-flex items-center justify-center rounded-2xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-700 transition hover:bg-red-100">
                                    Mark all as read
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Notifications -->
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($notifications as $notification)
                                                @php
                                                    $data = $notification->data;
                                                    $isUnread = is_null($notification->read_at);
                                                @endphp

                                                <div class="rounded-3xl border p-5 shadow-sm transition
                                                        {{ $isUnread
                                ? 'border-red-200 bg-gradient-to-br from-red-50 to-white'
                                : 'border-slate-200 bg-white' }}">
                                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                                        <div class="min-w-0 flex-1">
                                                            <div class="flex flex-wrap items-center gap-2">
                                                                @if($isUnread)
                                                                    <span
                                                                        class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700 ring-1 ring-red-200">
                                                                        New
                                                                    </span>
                                                                @else
                                                                    <span
                                                                        class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
                                                                        Read
                                                                    </span>
                                                                @endif

                                                                @if(!empty($data['blood_group']))
                                                                    <span
                                                                        class="inline-flex items-center rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700 ring-1 ring-red-100">
                                                                        {{ $data['blood_group'] }}
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <p class="mt-3 text-base font-semibold text-slate-900">
                                                                {{ $data['message'] ?? 'New notification' }}
                                                            </p>

                                                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                                                <div class="rounded-2xl bg-slate-50 p-4">
                                                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                                                        Patient
                                                                    </p>
                                                                    <p class="mt-1 text-sm font-medium text-slate-800">
                                                                        {{ $data['patient_name'] ?? '-' }}
                                                                    </p>
                                                                </div>

                                                                <div class="rounded-2xl bg-slate-50 p-4">
                                                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                                                        Contact
                                                                    </p>
                                                                    <p class="mt-1 text-sm font-medium text-slate-800">
                                                                        {{ $data['requester_phone'] ?? '-' }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <p class="mt-4 text-xs text-slate-500">
                                                                {{ $notification->created_at->diffForHumans() }}
                                                            </p>
                                                        </div>

                                                        <div class="flex shrink-0 flex-wrap gap-2">
                                                            @if(!empty($data['blood_request_id']))
                                                                <a href="{{ route('blood-requests.show', $data['blood_request_id']) }}"
                                                                    class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                                                                    View Request
                                                                </a>
                                                            @endif

                                                            @if($isUnread)
                                                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                                                    @csrf
                                                                    <button
                                                                        class="inline-flex items-center justify-center rounded-2xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-700 transition hover:bg-red-100">
                                                                        Mark read
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                            @empty
                                <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center">
                                    <div
                                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-3xl bg-red-50 text-red-600 ring-1 ring-red-100">
                                        <svg viewBox="0 0 24 24" class="h-7 w-7" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9" />
                                        </svg>
                                    </div>

                                    <h3 class="mt-5 text-lg font-semibold text-slate-900">
                                        No notifications yet
                                    </h3>

                                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                                        When there is blood request activity or alerts relevant to you, they will appear here.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    @if($notifications->hasPages())
                        <div class="px-6 pb-6">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                {{ $notifications->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
