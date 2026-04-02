<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-3xl bg-red-600 text-xl font-bold text-white shadow-sm shadow-red-200/70">
            🩸
        </div>

        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
            Create your account
        </h1>

        <p class="mt-2 text-sm leading-6 text-slate-500">
            Join the community, become a donor, and help save lives with accurate information.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Full Name')" class="text-sm font-medium text-slate-700" />
            <x-text-input
                id="name"
                class="mt-2 block w-full"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
                placeholder="e.g. MD. Mamun Ur Rashid"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-slate-700" />
            <x-text-input
                id="email"
                class="mt-2 block w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
                placeholder="name@example.com"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <div>
            <x-input-label for="phone" :value="__('Phone')" class="text-sm font-medium text-slate-700" />
            <x-text-input
                id="phone"
                class="mt-2 block w-full"
                type="tel"
                name="phone"
                :value="old('phone')"
                required
                autocomplete="tel"
                placeholder="01XXXXXXXXX"
            />
            <x-input-error :messages="$errors->get('phone')" class="mt-2 text-sm text-red-600" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-slate-700" />
            <x-text-input
                id="password"
                class="mt-2 block w-full"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Minimum 8 characters"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-medium text-slate-700" />
            <x-text-input
                id="password_confirmation"
                class="mt-2 block w-full"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Re-type your password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-primary w-full">
                {{ __('Register') }}
            </button>
        </div>

        <div class="text-center text-sm text-slate-500">
            Already registered?
            <a href="{{ route('login') }}" class="font-semibold text-red-600 transition hover:text-red-700">
                Log in here
            </a>
        </div>
    </form>

    <div class="mt-6 rounded-2xl border border-rose-100 bg-rose-50/60 px-4 py-3 text-center text-xs leading-5 text-slate-500">
        By creating an account, you agree to provide correct information and help responsibly.
    </div>
</x-guest-layout>