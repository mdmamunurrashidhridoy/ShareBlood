@extends('layouts.app')

@section('content')
    <div class="relative min-h-screen bg-slate-50">
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-red-500/10 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-red-500/10 blur-3xl"></div>
        </div>

        <div class="relative flex min-h-[calc(100vh-80px)] items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
            <div class="w-full max-w-2xl rounded-3xl border border-rose-100 bg-white p-8 shadow-sm sm:p-10">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-rose-100 text-3xl">
                    🚫
                </div>

                <div class="mt-6 text-center">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        Your account has been blocked
                    </h1>

                    <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">
                        You currently cannot use the platform because your account has been restricted by an administrator.
                    </p>

                    <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-left">
                        <p class="text-sm font-semibold text-slate-900">
                            Need help?
                        </p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            If you believe this was a mistake, please contact the administrator at
                            <a href="mailto:admin@example.com" class="font-semibold text-red-600 hover:text-red-700">
                                admin@example.com
                            </a>.
                        </p>
                    </div>

                    <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
