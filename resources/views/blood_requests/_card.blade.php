@php
    $isOwner = auth()->check() && $r->requester_user_id === auth()->id();
    $currentUser = auth()->user();
    $showMatchHints = $showMatchHints ?? true;

    $parts = [];
    if ($r->cityArea)
        $parts[] = $r->cityArea->name;
    if ($r->cityCorporation)
        $parts[] = $r->cityCorporation->name;
    if ($r->upazilla)
        $parts[] = $r->upazilla->name;
    if ($r->district)
        $parts[] = $r->district->name;
    if ($r->division)
        $parts[] = $r->division->name;

    $statusClasses = match ($r->status) {
        'pending' => 'border-amber-200 bg-amber-50 text-amber-800',
        'cancelled' => 'border-slate-200 bg-slate-100 text-slate-700',
        'completed' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
        default => 'border-slate-200 bg-slate-100 text-slate-700',
    };

    $rawPhone = $r->requester_phone;
    $cleanPhone = preg_replace('/\D+/', '', $rawPhone);

    if (str_starts_with($cleanPhone, '0')) {
        $whatsAppPhone = '88' . $cleanPhone;
    } elseif (str_starts_with($cleanPhone, '880')) {
        $whatsAppPhone = $cleanPhone;
    } else {
        $whatsAppPhone = $cleanPhone;
    }

    $whatsAppMessage = urlencode(
        "Assalamu Alaikum / Adab, I saw your blood request for {$r->patient_name}. I may be able to help. Please share the exact location and urgency."
    );

    $compatibleDonors = [
        'A+' => ['A+', 'A-', 'O+', 'O-'],
        'A-' => ['A-', 'O-'],
        'B+' => ['B+', 'B-', 'O+', 'O-'],
        'B-' => ['B-', 'O-'],
        'AB+' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
        'AB-' => ['A-', 'B-', 'AB-', 'O-'],
        'O+' => ['O+', 'O-'],
        'O-' => ['O-'],
    ];

    $userBloodGroup = auth()->check() ? $currentUser->blood_group : null;
    $requestBloodGroup = $r->blood_group;

    $matchesBloodGroup = auth()->check()
        && $userBloodGroup
        && isset($compatibleDonors[$requestBloodGroup])
        && in_array($userBloodGroup, $compatibleDonors[$requestBloodGroup], true);

    $isExactBloodGroupMatch = $matchesBloodGroup && $userBloodGroup === $requestBloodGroup;
    $isCompatibleButNotExact = $matchesBloodGroup && $userBloodGroup !== $requestBloodGroup;

    $matchesDivision = auth()->check()
        && $currentUser->division_id
        && $currentUser->division_id === $r->division_id;

    $matchesDistrict = auth()->check()
        && $currentUser->district_id
        && $currentUser->district_id === $r->district_id;

    $matchesUpazilla = auth()->check()
        && $currentUser->upazilla_id
        && $r->upazilla_id
        && $currentUser->upazilla_id === $r->upazilla_id;

    $matchesCityCorporation = auth()->check()
        && $currentUser->city_corporation_id
        && $r->city_corporation_id
        && $currentUser->city_corporation_id === $r->city_corporation_id;

    $matchesCityArea = auth()->check()
        && $currentUser->city_area_id
        && $r->city_area_id
        && $currentUser->city_area_id === $r->city_area_id;

    $hasLocalMatch =
        $matchesDistrict ||
        $matchesUpazilla ||
        $matchesCityCorporation ||
        $matchesCityArea;

    $matchLabel = null;
    $matchColor = null;

    if ($showMatchHints && !$isOwner) {
        if ($matchesBloodGroup && ($matchesUpazilla || $matchesCityArea)) {
            $matchLabel = $isExactBloodGroupMatch ? 'Best match for you' : 'Compatible nearby match';
            $matchColor = 'bg-red-100 text-red-700 ring-red-200';
        } elseif ($matchesBloodGroup && ($matchesDistrict || $matchesCityCorporation)) {
            $matchLabel = $isExactBloodGroupMatch ? 'Strong match' : 'Compatible local match';
            $matchColor = 'bg-amber-100 text-amber-700 ring-amber-200';
        } elseif ($isExactBloodGroupMatch) {
            $matchLabel = 'Matches your blood group';
            $matchColor = 'bg-emerald-100 text-emerald-700 ring-emerald-200';
        } elseif ($isCompatibleButNotExact) {
            $matchLabel = 'You can donate to this request';
            $matchColor = 'bg-sky-100 text-sky-700 ring-sky-200';
        }
    }

    $matchReasons = [];
    if ($matchesBloodGroup)
        $matchReasons[] = $isExactBloodGroupMatch ? 'Exact Blood Group' : 'Compatible Blood Group';
    if ($matchesDivision)
        $matchReasons[] = 'Division';
    if ($matchesDistrict)
        $matchReasons[] = 'District';
    if ($matchesUpazilla)
        $matchReasons[] = 'Upazilla';
    if ($matchesCityCorporation)
        $matchReasons[] = 'City Corporation';
    if ($matchesCityArea)
        $matchReasons[] = 'City Area';
@endphp


<div
    class="overflow-hidden rounded-[30px] border border-white/80 bg-white/90 p-5 shadow-[0_10px_35px_rgba(15,23,42,0.06)] backdrop-blur transition hover:-translate-y-0.5 hover:shadow-[0_18px_45px_rgba(15,23,42,0.08)] sm:p-6">

    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">

        <!-- Left content -->
        <div class="min-w-0 flex-1">

            <div class="flex flex-wrap items-center gap-2.5">

                <div
                    class="inline-flex items-center rounded-full bg-red-50 px-3 py-1 text-sm font-bold text-red-700 ring-1 ring-red-100">
                    {{ $r->blood_group }}
                </div>

                <span
                    class="inline-flex items-center gap-1.5 rounded-full bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                    {{ (int) ($r->quantity_bags ?? 1) }} bag{{ ((int) ($r->quantity_bags ?? 1)) === 1 ? '' : 's' }}
                </span>

                @if($r->is_emergency)
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700 ring-1 ring-red-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                        Emergency
                    </span>
                @endif

                <span
                    class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $statusClasses }}">
                    {{ ucfirst($r->status) }}
                </span>

                @if($isOwner)
                    <span
                        class="inline-flex items-center rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-200">
                        Your Request
                    </span>
                @endif

                @if($matchLabel)
                    <span
                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $matchColor }}">
                        {{ $matchLabel }}
                    </span>
                @endif

            </div>


            @if($showMatchHints && !$isOwner && $matchesBloodGroup && count($matchReasons))
                <div class="mt-3 rounded-2xl border border-slate-200 bg-white/70 px-3 py-3 text-xs text-slate-600">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-semibold text-slate-800">Matches:</span>

                        @foreach($matchReasons as $reason)
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 font-medium text-slate-700">
                                {{ $reason }}
                            </span>
                        @endforeach
                    </div>

                    @if($isCompatibleButNotExact)
                        <div class="mt-2 text-slate-500">
                            Requested: <span class="font-semibold text-slate-700">{{ $requestBloodGroup }}</span>
                            • Your group: <span class="font-semibold text-slate-700">{{ $userBloodGroup }}</span>
                            • You are a compatible donor
                        </div>
                    @endif
                </div>
            @endif

            <div class="mt-4 flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-slate-600">
                <span class="font-medium text-slate-800">Patient:</span>
                <span>{{ $r->patient_name }}</span>

                <span class="text-slate-300">•</span>

                <span class="font-medium text-slate-800">Needed:</span>
                <span>{{ $r->needed_date?->format('d M Y') }}</span>
            </div>

            <div class="mt-1 text-xs text-slate-500">
                Posted {{ $r->created_at?->diffForHumans() }}
            </div>

            @if($r->expires_at)
                <div class="mt-1 text-xs text-slate-500">
                    Expires {{ $r->expires_at->diffForHumans() }}
                </div>
            @endif


            <div class="mt-4 grid gap-3 sm:grid-cols-2">

                @if($r->hospital_name)
                    <div class="rounded-2xl border border-rose-100 bg-rose-50/50 p-4">
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Hospital
                        </div>
                        <div class="mt-1 text-sm font-medium text-slate-800">
                            {{ $r->hospital_name }}
                        </div>
                    </div>
                @endif

                <div class="rounded-2xl border border-slate-100 bg-white p-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Location
                    </div>
                    <div class="mt-1 text-sm font-medium text-slate-800">
                        {{ implode(', ', $parts) ?: 'Location not specified' }}
                    </div>
                </div>

            </div>


            <div class="mt-4 rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Requester
                </div>
                <div class="mt-1 text-sm text-slate-700">
                    <span class="font-medium text-slate-800">{{ $r->requester_name }}</span>
                    <span class="mx-2 text-slate-300">•</span>
                    <span class="font-mono text-slate-700">{{ $r->requester_phone }}</span>
                </div>
            </div>


            @if($r->address_line)
                <div class="mt-4 rounded-2xl border border-slate-100 bg-white p-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Address
                    </div>
                    <div class="mt-1 text-sm leading-6 text-slate-700">
                        {{ $r->address_line }}
                    </div>
                </div>
            @endif


            @if($r->note)
                <div class="mt-4 rounded-2xl border border-amber-100 bg-amber-50/70 p-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-amber-700">
                        Note
                    </div>
                    <div class="mt-1 text-sm leading-6 text-slate-700">
                        {{ $r->note }}
                    </div>
                </div>
            @endif


            <div class="mt-5 flex flex-wrap gap-2.5">

                <a href="{{ route('blood-requests.show', $r) }}"
                    class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                    View Details
                </a>

                @if($r->status === 'pending')

                    <a href="tel:{{ $r->requester_phone }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-700 shadow-sm transition hover:bg-red-100">
                        Call
                    </a>

                    <a href="https://wa.me/{{ $whatsAppPhone }}?text={{ $whatsAppMessage }}" target="_blank"
                        class="inline-flex items-center justify-center rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-700 shadow-sm transition hover:bg-emerald-100">
                        WhatsApp
                    </a>

                @endif

            </div>

        </div>


        <!-- Right actions -->
        @if($isOwner && $r->status === 'pending')
            <div class="w-full shrink-0 lg:w-48">
                <div class="rounded-[26px] border border-rose-100 bg-rose-50/50 p-3">

                    <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Owner Actions
                    </div>

                    <div class="space-y-2">

                        <form method="POST" action="{{ route('blood-requests.complete', $r) }}">
                            @csrf
                            @method('PATCH')

                            <button
                                class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                                Mark Completed
                            </button>
                        </form>


                        <form method="POST" action="{{ route('blood-requests.cancel', $r) }}">
                            @csrf
                            @method('PATCH')

                            <button
                                class="inline-flex w-full items-center justify-center rounded-2xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700">
                                Cancel Request
                            </button>
                        </form>

                    </div>

                </div>
            </div>
        @endif

    </div>
</div>
