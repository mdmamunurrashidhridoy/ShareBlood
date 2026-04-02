<nav x-data="{ open: false }"
    class="sticky top-0 z-40 border-b border-white/70 bg-white/80 backdrop-blur supports-[backdrop-filter]:bg-white/70">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex min-h-[72px] items-center justify-between gap-4">
            <!-- Left -->
            <div class="flex items-center gap-8">
                <a href="{{ auth()->check() ? route('dashboard') : route('landingPage') }}"
                    class="group flex items-center gap-3">
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-2xl bg-red-600 text-lg text-white shadow-sm shadow-red-200/60 transition group-hover:scale-[1.02]">
                        🩸
                    </div>

                    <div class="hidden leading-tight sm:block">
                        <div
                            class="text-sm font-semibold tracking-tight text-slate-900 transition group-hover:text-red-600">
                            {{ config('app.name', 'Blood Donation') }}
                        </div>
                        <div class="mt-0.5 text-xs text-slate-500">
                            Find donors with clarity and care
                        </div>
                    </div>
                </a>

                <div class="hidden items-center gap-1.5 lg:flex">
                    <a href="{{ route('donors.index') }}" class="rounded-full px-4 py-2 text-sm font-medium transition
                        {{ request()->routeIs('donors.index')
    ? 'bg-red-50 text-red-700 ring-1 ring-red-100'
    : 'text-slate-600 hover:bg-rose-50 hover:text-slate-900' }}">
                        Find Donor
                    </a>

                    <a href="{{ route('blood-requests.index') }}" class="rounded-full px-4 py-2 text-sm font-medium transition
                        {{ request()->routeIs('blood-requests.index')
    ? 'bg-red-50 text-red-700 ring-1 ring-red-100'
    : 'text-slate-600 hover:bg-rose-50 hover:text-slate-900' }}">
                        All Requests
                    </a>

                    @auth
                                    <a href="{{ route('dashboard') }}" class="rounded-full px-4 py-2 text-sm font-medium transition
                                            {{ request()->routeIs('dashboard')
                        ? 'bg-red-50 text-red-700 ring-1 ring-red-100'
                        : 'text-slate-600 hover:bg-rose-50 hover:text-slate-900' }}">
                                        Dashboard
                                    </a>

                                    <a href="{{ route('profile.show') }}" class="rounded-full px-4 py-2 text-sm font-medium transition
                                            {{ request()->routeIs('profile.show')
                        ? 'bg-red-50 text-red-700 ring-1 ring-red-100'
                        : 'text-slate-600 hover:bg-rose-50 hover:text-slate-900' }}">
                                        Profile
                                    </a>

                                    <a href="{{ route('blood-requests.my') }}" class="rounded-full px-4 py-2 text-sm font-medium transition
                                            {{ request()->routeIs('blood-requests.my')
                        ? 'bg-red-50 text-red-700 ring-1 ring-red-100'
                        : 'text-slate-600 hover:bg-rose-50 hover:text-slate-900' }}">
                                        My Requests
                                    </a>
                    @endauth
                </div>
            </div>

            <!-- Right -->
            <div class="hidden items-center gap-3 sm:flex">
                @auth
                    @php
                        $unreadCount = auth()->user()->unreadNotifications()->count();
                        $latestNotifications = auth()->user()->notifications()->latest()->take(5)->get();
                    @endphp

                    <div class="hidden text-sm text-slate-500 md:block">
                        Welcome,
                        <span class="font-semibold text-slate-800">{{ auth()->user()->name }}</span>
                    </div>

                    <!-- Notifications -->
                    <div x-data="{ notifyOpen: false }" class="relative">
                        <button type="button" @click="notifyOpen = !notifyOpen" @click.outside="notifyOpen = false"
                            class="relative inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-rose-100 bg-white text-slate-600 shadow-sm transition hover:border-rose-200 hover:bg-rose-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0m6 0H9" />
                            </svg>

                            @if ($unreadCount > 0)
                                <span
                                    class="absolute -right-1.5 -top-1.5 inline-flex min-h-[20px] min-w-[20px] items-center justify-center rounded-full bg-red-600 px-1.5 text-[11px] font-bold text-white shadow-sm">
                                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                </span>
                            @endif
                        </button>

                        <div x-cloak x-show="notifyOpen" x-transition
                            class="absolute right-0 z-50 mt-3 w-[24rem] overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-200/60">
                            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-4">
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900">Notifications</h3>
                                    <p class="mt-0.5 text-xs text-slate-500">{{ $unreadCount }} unread</p>
                                </div>

                                @if ($unreadCount > 0)
                                    <form method="POST" action="{{ route('notifications.readAll') }}">
                                        @csrf
                                        <button type="submit"
                                            class="text-xs font-semibold text-red-600 transition hover:text-red-700">
                                            Mark all read
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="max-h-[26rem] overflow-y-auto">
                                @forelse ($latestNotifications as $notification)
                                    @php
                                        $data = $notification->data;
                                        $isUnread = is_null($notification->read_at);
                                    @endphp

                                    <div
                                        class="border-b border-slate-100 px-4 py-4 last:border-b-0 {{ $isUnread ? 'bg-red-50/40' : 'bg-white' }}">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-2">
                                                    <p class="truncate text-sm font-medium text-slate-900">
                                                        {{ $data['title'] ?? $data['message'] ?? 'New notification' }}
                                                    </p>

                                                    @if ($isUnread)
                                                        <span class="inline-flex h-2 w-2 rounded-full bg-red-500"></span>
                                                    @endif
                                                </div>

                                                <p class="mt-1 text-sm text-slate-600">
                                                    {{ $data['message'] ?? 'You have a new notification.' }}
                                                </p>

                                                <p class="mt-1 text-xs text-slate-500">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>

                                                @if (!empty($data['action_url']))
                                                    <a href="{{ $data['action_url'] }}"
                                                        class="mt-2 inline-flex text-xs font-semibold text-red-600 hover:text-red-700">
                                                        View details
                                                    </a>
                                                @endif
                                            </div>

                                            @if ($isUnread)
                                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                                    @csrf
                                                    <button type="submit"
                                                        class="rounded-full bg-red-600 px-3 py-1.5 text-[11px] font-semibold text-white transition hover:bg-red-700">
                                                        Read
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="px-4 py-10 text-center">
                                        <div
                                            class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50 text-xl ring-1 ring-slate-200">
                                            🔔
                                        </div>
                                        <p class="mt-3 text-sm font-medium text-slate-800">No notifications</p>
                                        <p class="mt-1 text-xs text-slate-500">You’re all caught up.</p>
                                    </div>
                                @endforelse
                            </div>

                            <div class="border-t border-slate-100 bg-slate-50/70 px-4 py-3">
                                <a href="{{ route('notifications.index') }}"
                                    class="block rounded-2xl bg-white px-4 py-2.5 text-center text-sm font-semibold text-slate-700 ring-1 ring-slate-200 transition hover:bg-rose-50 hover:text-red-700">
                                    View all notifications
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Account dropdown -->
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center gap-3 rounded-full border border-rose-100 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-rose-200 hover:bg-rose-50/60 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                <span
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 font-semibold text-red-700 ring-1 ring-rose-100">
                                    {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                                </span>

                                <span class="hidden md:block">Account</span>

                                <svg class="h-4 w-4 opacity-60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard')">
                                Dashboard
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('profile.show')">
                                Profile
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('donors.index')">
                                Find Donor
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('blood-requests.index')">
                                All Requests
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('blood-requests.my')">
                                My Requests
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('notifications.index')">
                                Notifications
                            </x-dropdown-link>

                            <div class="my-1 border-t border-slate-100"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth

                @guest
                    <a href="{{ route('login') }}"
                        class="rounded-full px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-rose-50 hover:text-slate-900">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center justify-center rounded-full bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-red-200/70 transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Become a Donor
                        </a>
                    @endif
                @endguest
            </div>

            <!-- Mobile toggle -->
            <div class="sm:hidden">
                <button @click="open = !open"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-rose-100 bg-white text-slate-600 shadow-sm transition hover:bg-rose-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-red-500">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-cloak :class="{ 'block': open, 'hidden': !open }"
        class="hidden border-t border-rose-100 bg-white/95 sm:hidden">
        <div class="space-y-2 px-4 py-4">
            <a href="{{ route('donors.index') }}" class="block rounded-2xl px-4 py-3 text-sm font-medium transition
                {{ request()->routeIs('donors.index')
    ? 'bg-red-50 text-red-700'
    : 'text-slate-700 hover:bg-rose-50' }}">
                Find Donor
            </a>

            <a href="{{ route('blood-requests.index') }}" class="block rounded-2xl px-4 py-3 text-sm font-medium transition
                {{ request()->routeIs('blood-requests.index')
    ? 'bg-red-50 text-red-700'
    : 'text-slate-700 hover:bg-rose-50' }}">
                All Requests
            </a>

            @auth
                    <a href="{{ route('dashboard') }}" class="block rounded-2xl px-4 py-3 text-sm font-medium transition
                            {{ request()->routeIs('dashboard')
                ? 'bg-red-50 text-red-700'
                : 'text-slate-700 hover:bg-rose-50' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('profile.show') }}" class="block rounded-2xl px-4 py-3 text-sm font-medium transition
                            {{ request()->routeIs('profile.show')
                ? 'bg-red-50 text-red-700'
                : 'text-slate-700 hover:bg-rose-50' }}">
                        Profile
                    </a>

                    <a href="{{ route('blood-requests.my') }}" class="block rounded-2xl px-4 py-3 text-sm font-medium transition
                            {{ request()->routeIs('blood-requests.my')
                ? 'bg-red-50 text-red-700'
                : 'text-slate-700 hover:bg-rose-50' }}">
                        My Requests
                    </a>

                    <a href="{{ route('notifications.index') }}" class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium transition
                            {{ request()->routeIs('notifications.*')
                ? 'bg-red-50 text-red-700'
                : 'text-slate-700 hover:bg-rose-50' }}">
                        <span>Notifications</span>

                        @if (auth()->user()->unreadNotifications()->count() > 0)
                            <span
                                class="inline-flex min-h-[20px] min-w-[20px] items-center justify-center rounded-full bg-red-600 px-1.5 text-[11px] font-bold text-white">
                                {{ auth()->user()->unreadNotifications()->count() > 99 ? '99+' : auth()->user()->unreadNotifications()->count() }}
                            </span>
                        @endif
                    </a>

                    <div class="mt-4 rounded-3xl border border-rose-100 bg-rose-50/40 p-4">
                        <div class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</div>
                        <div class="mt-1 text-sm text-slate-500">{{ auth()->user()->email }}</div>

                        <form method="POST" action="{{ route('logout') }}" class="mt-4">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-2xl bg-red-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700">
                                Log Out
                            </button>
                        </form>
                    </div>
            @endauth

            @guest
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <a href="{{ route('login') }}"
                        class="rounded-2xl border border-rose-100 bg-white px-4 py-3 text-center text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-rose-50">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="rounded-2xl bg-red-600 px-4 py-3 text-center text-sm font-semibold text-white shadow-sm transition hover:bg-red-700">
                            Register
                        </a>
                    @endif
                </div>
            @endguest
        </div>
    </div>
</nav>
