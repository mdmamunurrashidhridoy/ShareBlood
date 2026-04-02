<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-3xl bg-red-600 text-xl font-bold text-white shadow-sm shadow-red-200/70">
            🩸
        </div>

        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
            Welcome back
        </h1>

        <p class="mt-2 text-sm leading-6 text-slate-500">
            Log in to manage your donor profile and blood requests with ease.
        </p>
    </div>

    <x-auth-session-status class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-slate-700" />
            <x-text-input
                id="email"
                class="mt-2 block w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                placeholder="name@example.com"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <div>
            <div class="flex items-center justify-between gap-3">
                <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-slate-700" />

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-red-600 transition hover:text-red-700">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <x-text-input
                id="password"
                class="mt-2 block w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Your password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <label for="remember_me" class="flex items-center gap-3 rounded-2xl border border-rose-100 bg-rose-50/50 px-4 py-3">
            <input
                id="remember_me"
                type="checkbox"
                name="remember"
                class="rounded border-slate-300 text-red-600 shadow-sm focus:ring-red-500"
            >
            <span class="text-sm text-slate-600">
                {{ __('Remember me on this device') }}
            </span>
        </label>

        <div class="pt-2">
            <button type="submit" class="btn-primary w-full">
                {{ __('Log in') }}
            </button>
        </div>

        @if (Route::has('register'))
            <div class="text-center text-sm text-slate-500">
                New here?
                <a href="{{ route('register') }}" class="font-semibold text-red-600 transition hover:text-red-700">
                    Create an account
                </a>
            </div>
        @endif
    </form>

    <div class="mt-6 rounded-2xl border border-rose-100 bg-rose-50/60 px-4 py-3 text-center text-xs leading-5 text-slate-500">
        Need blood urgently? Log in first to create and manage a blood request.
    </div>
</x-guest-layout>