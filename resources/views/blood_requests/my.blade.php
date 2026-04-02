@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl py-6 sm:py-8">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="min-w-0">
                <div class="badge-soft">Your activity</div>
                <h1 class="mt-4 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">
                    My Requests
                </h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600 sm:text-base">
                    Track your blood requests, review their status, and manage them from one place.
                </p>
            </div>

            <div class="shrink-0">
                <a href="{{ route('blood-requests.create') }}" class="btn-primary w-full sm:w-auto">
                    Create Request
                </a>
            </div>
        </div>

        @if(session('success'))
            <div
                class="mb-6 rounded-[24px] border border-emerald-200 bg-emerald-50/90 px-5 py-4 text-sm text-emerald-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-5">
            @forelse($requests as $r)
                @include('blood_requests._card', ['r' => $r, 'showMatchHints' => false])
            @empty
                <div class="card-soft px-6 py-10 text-center sm:px-10">
                    <div
                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-3xl bg-rose-50 text-red-600 ring-1 ring-rose-100">
                        <svg viewBox="0 0 24 24" class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2s7 7 7 12a7 7 0 0 1-14 0c0-5 7-12 7-12z" />
                        </svg>
                    </div>

                    <h3 class="mt-5 text-lg font-semibold text-slate-900">
                        No requests yet
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                        You have not created any blood requests yet. When help is needed, create your first request here.
                    </p>

                    <div class="mt-6">
                        <a href="{{ route('blood-requests.create') }}" class="btn-primary">
                            Create Request
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($requests->hasPages())
            <div class="mt-8">
                <div class="card-soft px-4 py-3">
                    {{ $requests->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
