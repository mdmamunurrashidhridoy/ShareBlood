@extends('layouts.app')

@section('content')
    <section class="mx-auto max-w-6xl pt-4 pb-10 lg:pt-8">
        <div class="grid gap-12 lg:grid-cols-2 lg:items-center">
            <div>
                <div class="badge-soft">
                    <span class="mr-2 h-2 w-2 rounded-full bg-red-500"></span>
                    Bangladesh Blood Donation Network
                </div>

                <h1 class="mt-5 text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl lg:text-6xl">
                    Find a donor with calm,
                    <span class="text-red-600">request blood with confidence.</span>
                </h1>

                <p class="mt-5 max-w-2xl text-base leading-7 text-slate-600 sm:text-lg">
                    A gentle and reliable platform that connects blood donors and patients by blood group and location.
                    Search nearby donors, create urgent requests, and help save lives with clarity and care.
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                    <a href="{{ route('donors.index') }}" class="btn-primary">
                        Find Blood Donor
                    </a>

                    <a href="{{ Route::has('blood-requests.create') ? route('blood-requests.create') : (Route::has('login') ? route('login') : '#') }}"
                        class="btn-secondary">
                        Create Blood Request
                    </a>

                    <a href="{{ route('blood-requests.index') }}" class="btn-secondary">
                        Browse Requests
                    </a>
                </div>

                <div class="mt-8 grid gap-4 sm:grid-cols-3">
                    <div class="card-soft p-5">
                        <div class="text-xs font-medium uppercase tracking-wide text-slate-500">Response</div>
                        <div class="mt-2 text-xl font-semibold text-slate-900">Quick</div>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Get help faster in urgent situations.
                        </p>
                    </div>

                    <div class="card-soft p-5">
                        <div class="text-xs font-medium uppercase tracking-wide text-slate-500">Match by</div>
                        <div class="mt-2 text-xl font-semibold text-slate-900">Location</div>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Find donors close to the patient.
                        </p>
                    </div>

                    <div class="card-soft p-5">
                        <div class="text-xs font-medium uppercase tracking-wide text-slate-500">Built for</div>
                        <div class="mt-2 text-xl font-semibold text-slate-900">Trust</div>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            A calm community-first donation experience.
                        </p>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -inset-4 rounded-[36px] bg-gradient-to-br from-rose-100/80 via-white to-amber-50/70 blur-2xl"></div>

                <div class="card-soft relative overflow-hidden p-6 sm:p-7">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-sm font-semibold text-slate-900">Emergency Request Preview</div>
                            <div class="mt-1 text-sm leading-6 text-slate-600">
                                A quick look at how urgent blood requests can appear.
                            </div>
                        </div>

                        <span class="inline-flex items-center rounded-full bg-red-600 px-3 py-1 text-xs font-semibold text-white shadow-sm">
                            URGENT
                        </span>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div class="rounded-3xl border border-rose-100 bg-rose-50/60 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div class="text-sm font-medium text-slate-700">Blood Group</div>
                                <div class="text-base font-semibold text-red-600">B+</div>
                            </div>
                            <div class="mt-2 text-sm text-slate-600">
                                Needed: 3 bags
                            </div>
                            <div class="mt-1 text-xs text-slate-500">
                                Date: 07 Mar 2026
                            </div>
                        </div>

                        <div class="rounded-3xl border border-slate-100 bg-white p-4">
                            <div class="text-sm font-medium text-slate-700">Location</div>
                            <div class="mt-2 text-sm font-medium text-slate-900">
                                Dhaka • City Corporation Area
                            </div>
                            <div class="mt-1 text-xs text-slate-500">
                                Matches nearby donors based on selected area.
                            </div>
                        </div>

                        <div class="rounded-3xl border border-slate-100 bg-amber-50/60 p-4">
                            <div class="text-sm font-semibold text-slate-900">What happens next?</div>
                            <div class="mt-2 text-sm leading-6 text-slate-600">
                                Matching donors can quickly see the request and respond with care.
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <div class="text-xs font-medium uppercase tracking-wide text-slate-500">
                            Search by blood group
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2">
                            @php($groups = ['A+','A-','B+','B-','O+','O-','AB+','AB-'])
                            @foreach($groups as $g)
                                <span class="inline-flex items-center rounded-full border border-rose-100 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm">
                                    {{ $g }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl py-10">
        <div class="max-w-3xl">
            <div class="badge-soft">How it works</div>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-slate-900">
                Three simple steps to connect help with hope
            </h2>
            <p class="mt-3 text-base leading-7 text-slate-600">
                Designed around Bangladesh’s location structure so donors and requests can be matched more clearly and quickly.
            </p>
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-3">
            <div class="card-soft p-6">
                <div class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-50 text-sm font-bold text-red-600 ring-1 ring-rose-100">
                    1
                </div>
                <div class="mt-4 text-lg font-semibold text-slate-900">Complete profile</div>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Add blood group, phone number, and location so others can find you more easily when needed.
                </p>
            </div>

            <div class="card-soft p-6">
                <div class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-50 text-sm font-bold text-red-600 ring-1 ring-rose-100">
                    2
                </div>
                <div class="mt-4 text-lg font-semibold text-slate-900">Create a request</div>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Post the needed blood group, date, and location so nearby donors can understand the urgency clearly.
                </p>
            </div>

            <div class="card-soft p-6">
                <div class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-50 text-sm font-bold text-red-600 ring-1 ring-rose-100">
                    3
                </div>
                <div class="mt-4 text-lg font-semibold text-slate-900">Match nearby donors</div>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Connect people faster through location-based matching and make urgent moments feel more manageable.
                </p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl py-10">
        <div class="max-w-3xl">
            <div class="badge-soft">Blood donation basics</div>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-slate-900">
                Blood Compatibility Guide
            </h2>
            <p class="mt-3 text-base leading-7 text-slate-600">
                A quick reference for who can donate blood to whom, and who can receive from whom.
                This is a simple red blood cell donation guide for awareness.
            </p>
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="card-soft p-5">
                <div class="badge-soft">A+</div>
                <div class="mt-4">
                    <h3 class="text-sm font-semibold text-slate-900">Can donate to</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">A+, AB+</p>
                </div>
                <div class="mt-4 border-t border-rose-100 pt-4">
                    <h3 class="text-sm font-semibold text-slate-900">Can receive from</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">A+, A-, O+, O-</p>
                </div>
            </div>

            <div class="card-soft p-5">
                <div class="badge-soft">A-</div>
                <div class="mt-4">
                    <h3 class="text-sm font-semibold text-slate-900">Can donate to</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">A+, A-, AB+, AB-</p>
                </div>
                <div class="mt-4 border-t border-rose-100 pt-4">
                    <h3 class="text-sm font-semibold text-slate-900">Can receive from</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">A-, O-</p>
                </div>
            </div>

            <div class="card-soft p-5">
                <div class="badge-soft">B+</div>
                <div class="mt-4">
                    <h3 class="text-sm font-semibold text-slate-900">Can donate to</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">B+, AB+</p>
                </div>
                <div class="mt-4 border-t border-rose-100 pt-4">
                    <h3 class="text-sm font-semibold text-slate-900">Can receive from</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">B+, B-, O+, O-</p>
                </div>
            </div>

            <div class="card-soft p-5">
                <div class="badge-soft">B-</div>
                <div class="mt-4">
                    <h3 class="text-sm font-semibold text-slate-900">Can donate to</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">B+, B-, AB+, AB-</p>
                </div>
                <div class="mt-4 border-t border-rose-100 pt-4">
                    <h3 class="text-sm font-semibold text-slate-900">Can receive from</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">B-, O-</p>
                </div>
            </div>

            <div class="card-soft p-5">
                <div class="badge-soft">AB+</div>
                <div class="mt-4">
                    <h3 class="text-sm font-semibold text-slate-900">Can donate to</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">AB+</p>
                </div>
                <div class="mt-4 border-t border-rose-100 pt-4">
                    <h3 class="text-sm font-semibold text-slate-900">Can receive from</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">All blood groups</p>
                </div>
            </div>

            <div class="card-soft p-5">
                <div class="badge-soft">AB-</div>
                <div class="mt-4">
                    <h3 class="text-sm font-semibold text-slate-900">Can donate to</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">AB+, AB-</p>
                </div>
                <div class="mt-4 border-t border-rose-100 pt-4">
                    <h3 class="text-sm font-semibold text-slate-900">Can receive from</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">A-, B-, AB-, O-</p>
                </div>
            </div>

            <div class="card-soft p-5">
                <div class="badge-soft">O+</div>
                <div class="mt-4">
                    <h3 class="text-sm font-semibold text-slate-900">Can donate to</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">O+, A+, B+, AB+</p>
                </div>
                <div class="mt-4 border-t border-rose-100 pt-4">
                    <h3 class="text-sm font-semibold text-slate-900">Can receive from</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">O+, O-</p>
                </div>
            </div>

            <div class="card-soft p-5">
                <div class="badge-soft">O-</div>
                <div class="mt-4">
                    <h3 class="text-sm font-semibold text-slate-900">Can donate to</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">All blood groups</p>
                </div>
                <div class="mt-4 border-t border-rose-100 pt-4">
                    <h3 class="text-sm font-semibold text-slate-900">Can receive from</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">O- only</p>
                </div>
            </div>
        </div>

        <div class="mt-6 rounded-3xl border border-amber-200 bg-amber-50/80 px-5 py-4 text-sm leading-6 text-amber-800">
            Always follow hospital screening, doctor advice, and crossmatch requirements before transfusion.
        </div>
    </section>

    <section class="mx-auto max-w-6xl pb-16 pt-4">
        <div class="overflow-hidden rounded-[32px] border border-white/80 bg-gradient-to-br from-slate-900 to-slate-800 p-8 text-white shadow-[0_10px_40px_rgba(15,23,42,0.10)] sm:p-10">
            <div class="grid gap-8 md:grid-cols-2 md:items-center">
                <div>
                    <div class="inline-flex rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white/80">
                        Every minute matters
                    </div>

                    <h3 class="mt-4 text-3xl font-semibold tracking-tight">
                        Ready to help save a life today?
                    </h3>

                    <p class="mt-3 max-w-xl text-sm leading-7 text-white/80 sm:text-base">
                        Join as a donor or create a blood request now. A calm, clear platform can make urgent moments easier to handle.
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row md:justify-end">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                        Register as Donor
                    </a>

                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full border border-white/20 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        Log in
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer class="border-t border-white/70 py-8 text-center text-sm text-slate-500">
        © {{ date('Y') }} {{ config('app.name', 'Blood Donation') }} • Made with care using Laravel
    </footer>
@endsection