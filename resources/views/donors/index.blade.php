@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl py-6 sm:py-8">
    <div class="mb-8">
        <div class="badge-soft">Donor search</div>
        <h1 class="mt-4 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">
            Find Blood Donors
        </h1>
        <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600 sm:text-base">
            Search available and eligible donors by blood group and location, with a calm and clear matching flow.
        </p>
    </div>

    <div class="card-soft mb-8 p-5 sm:p-6">
        <form method="GET" action="{{ route('donors.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Blood Group</label>
                <select name="blood_group" class="mt-2">
                    <option value="">All Blood Groups</option>
                    @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $group)
                        <option value="{{ $group }}" @selected(request('blood_group') == $group)>{{ $group }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Division</label>
                <select id="division_id" name="division_id" class="mt-2">
                    <option value="">All Divisions</option>
                    @foreach ($divisions as $division)
                        <option value="{{ $division->id }}" @selected(request('division_id') == $division->id)>
                            {{ $division->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">District</label>
                <select id="district_id" name="district_id" class="mt-2">
                    <option value="">All Districts</option>
                    @foreach ($districts as $district)
                        <option value="{{ $district->id }}" @selected(request('district_id') == $district->id)>
                            {{ $district->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Location Type</label>
                <select id="location_type" class="mt-2">
                    <option value="">All</option>
                    <option value="upazilla" {{ request('upazilla_id') ? 'selected' : '' }}>Upazilla</option>
                    <option value="city" {{ request('city_corporation_id') || request('city_area_id') ? 'selected' : '' }}>City Corporation</option>
                </select>
            </div>

            <div id="upazilla_wrapper">
                <label class="block text-sm font-medium text-slate-700">Upazilla</label>
                <select id="upazilla_id" name="upazilla_id" class="mt-2">
                    <option value="">All Upazillas</option>
                    @foreach ($upazillas as $upazilla)
                        <option value="{{ $upazilla->id }}" @selected(request('upazilla_id') == $upazilla->id)>
                            {{ $upazilla->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="city_corporation_wrapper">
                <label class="block text-sm font-medium text-slate-700">City Corporation</label>
                <select id="city_corporation_id" name="city_corporation_id" class="mt-2">
                    <option value="">All City Corporations</option>
                    @foreach ($cityCorporations as $cityCorporation)
                        <option value="{{ $cityCorporation->id }}" @selected(request('city_corporation_id') == $cityCorporation->id)>
                            {{ $cityCorporation->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="city_area_wrapper">
                <label class="block text-sm font-medium text-slate-700">City Area</label>
                <select id="city_area_id" name="city_area_id" class="mt-2">
                    <option value="">All City Areas</option>
                    @foreach ($cityAreas as $cityArea)
                        <option value="{{ $cityArea->id }}" @selected(request('city_area_id') == $cityArea->id)>
                            {{ $cityArea->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center">
                <label class="mt-6 flex w-full items-center gap-3 rounded-2xl border border-rose-100 bg-rose-50/50 px-4 py-3">
                    <input
                        id="available_only"
                        type="checkbox"
                        name="available_only"
                        value="1"
                        {{ request('available_only', '1') ? 'checked' : '' }}
                        class="rounded border-slate-300 text-red-600 focus:ring-red-500"
                    >
                    <div>
                        <div class="text-sm font-medium text-slate-800">Available only</div>
                        <div class="text-xs text-slate-500">Show donors ready to respond now</div>
                    </div>
                </label>
            </div>

            <div class="flex items-center">
                <label class="mt-6 flex w-full items-center gap-3 rounded-2xl border border-amber-100 bg-amber-50/60 px-4 py-3">
                    <input
                        id="eligible_only"
                        type="checkbox"
                        name="eligible_only"
                        value="1"
                        {{ request('eligible_only', '1') ? 'checked' : '' }}
                        class="rounded border-slate-300 text-red-600 focus:ring-red-500"
                    >
                    <div>
                        <div class="text-sm font-medium text-slate-800">Eligible only</div>
                        <div class="text-xs text-slate-500">Hide donors not yet eligible to donate</div>
                    </div>
                </label>
            </div>

            <div class="xl:col-span-4 flex flex-col gap-3 border-t border-rose-100 pt-4 sm:flex-row sm:items-center">
                <button type="submit" class="btn-primary w-full sm:w-auto">
                    Search Donors
                </button>

                <a href="{{ route('donors.index') }}" class="btn-secondary w-full sm:w-auto">
                    Reset Filters
                </a>
            </div>
        </form>
    </div>

    <div class="mb-5 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">
                Donors Found: {{ $donors->total() }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Matching donors based on the selected filters.
            </p>
        </div>
    </div>

    @if ($donors->count())
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($donors as $donor)
                @php
                    $profile = $donor->donorProfile;
                    $isEligible = is_null($profile?->next_eligible_date)
                        || $profile->next_eligible_date->isPast()
                        || $profile->next_eligible_date->isToday();

                    $location = collect([
                        $donor->cityArea?->name,
                        $donor->cityCorporation?->name,
                        $donor->upazilla?->name,
                        $donor->district?->name,
                        $donor->division?->name,
                    ])->filter()->implode(', ');
                @endphp

                <div class="card-soft p-6">
                    <div class="mb-4 flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <h3 class="text-lg font-semibold text-slate-900">
                                {{ $donor->name }}
                            </h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Registered blood donor
                            </p>
                        </div>

                        <span class="badge-soft shrink-0">
                            {{ $donor->blood_group ?? 'N/A' }}
                        </span>
                    </div>

                    <div class="space-y-3 text-sm text-slate-600">
                        <div class="rounded-2xl border border-slate-100 bg-white p-4">
                            <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Phone</div>
                            @php
    $phone = $donor->phone;
    $waPhone = '88' . ltrim($phone, '0');
@endphp

<div class="mt-3 flex gap-2">

    <!-- Call -->
    <a href="tel:{{ $phone }}"
       class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-3 py-2 text-xs font-semibold text-white hover:bg-red-700">

        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 5a2 2 0 012-2h2l2 5-2 1a11 11 0 005 5l1-2 5 2v2a2 2 0 01-2 2h-1C8 21 3 16 3 9V5z"/>
        </svg>

        Call
    </a>

    <!-- WhatsApp -->
    <a href="https://wa.me/{{ $waPhone }}"
       target="_blank"
       class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">

        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
             viewBox="0 0 32 32" fill="currentColor">
            <path d="M19.11 17.42c-.27-.13-1.6-.79-1.84-.88-.25-.09-.43-.13-.61.13s-.7.88-.86 1.06c-.16.18-.31.2-.58.07-.27-.13-1.14-.42-2.17-1.34-.8-.71-1.34-1.59-1.5-1.86-.16-.27-.02-.42.11-.55.12-.12.27-.31.4-.47.13-.16.18-.27.27-.45.09-.18.04-.34-.02-.47-.07-.13-.61-1.47-.83-2.02-.22-.52-.44-.45-.61-.46-.16-.01-.34-.01-.52-.01s-.47.07-.72.34c-.25.27-.95.93-.95 2.27s.97 2.64 1.11 2.82c.13.18 1.91 2.91 4.64 4.08.65.28 1.16.45 1.56.58.66.21 1.26.18 1.73.11.53-.08 1.6-.65 1.83-1.28.22-.63.22-1.17.16-1.28-.07-.11-.25-.18-.52-.31z"/>
        </svg>

        WhatsApp
    </a>

</div>
                        </div>

                        <div class="rounded-2xl border border-slate-100 bg-white p-4">
                            <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Location</div>
                            <div class="mt-1 leading-6 text-slate-700">{{ $location ?: 'Not specified' }}</div>
                        </div>

                        <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                            <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Last Donation</div>
                            <div class="mt-1 text-slate-700">
                                {{ $profile?->last_donate_date?->format('d M Y') ?? 'No record yet' }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $profile?->is_available ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-slate-100 text-slate-700 ring-slate-200' }}">
                            {{ $profile?->is_available ? 'Available' : 'Unavailable' }}
                        </span>

                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $isEligible ? 'bg-blue-50 text-blue-700 ring-blue-200' : 'bg-amber-50 text-amber-700 ring-amber-200' }}">
                            {{ $isEligible ? 'Eligible' : 'Not Eligible Yet' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            <div class="card-soft px-4 py-3">
                {{ $donors->links() }}
            </div>
        </div>
    @else
        <div class="card-soft p-10 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-3xl bg-rose-50 text-red-600 ring-1 ring-rose-100">
                <svg viewBox="0 0 24 24" class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2s7 7 7 12a7 7 0 0 1-14 0c0-5 7-12 7-12z"/>
                </svg>
            </div>

            <h3 class="mt-5 text-lg font-semibold text-slate-900">No donors found</h3>
            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                Try changing blood group or location filters to find more matching donors.
            </p>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const divisionSelect = document.getElementById('division_id');
    const districtSelect = document.getElementById('district_id');
    const upazillaSelect = document.getElementById('upazilla_id');
    const cityCorporationSelect = document.getElementById('city_corporation_id');
    const cityAreaSelect = document.getElementById('city_area_id');
    const locationTypeSelect = document.getElementById('location_type');

    const upazillaWrapper = document.getElementById('upazilla_wrapper');
    const cityCorporationWrapper = document.getElementById('city_corporation_wrapper');
    const cityAreaWrapper = document.getElementById('city_area_wrapper');

    function resetSelect(select, placeholder) {
        select.innerHTML = `<option value="">${placeholder}</option>`;
    }

    function populateSelect(select, items, placeholder, selectedValue = '') {
        resetSelect(select, placeholder);

        items.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;

            if (String(selectedValue) === String(item.id)) {
                option.selected = true;
            }

            select.appendChild(option);
        });
    }

    function toggleLocationFields() {
        const type = locationTypeSelect.value;

        if (type === 'upazilla') {
            upazillaWrapper.style.display = '';
            cityCorporationWrapper.style.display = 'none';
            cityAreaWrapper.style.display = 'none';

            cityCorporationSelect.value = '';
            cityAreaSelect.value = '';
        } else if (type === 'city') {
            upazillaWrapper.style.display = 'none';
            cityCorporationWrapper.style.display = '';
            cityAreaWrapper.style.display = '';

            upazillaSelect.value = '';
        } else {
            upazillaWrapper.style.display = '';
            cityCorporationWrapper.style.display = '';
            cityAreaWrapper.style.display = '';
        }
    }

    async function fetchData(url, params = {}) {
        const query = new URLSearchParams(params).toString();
        const response = await fetch(`${url}?${query}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch data');
        }

        return await response.json();
    }

    divisionSelect.addEventListener('change', async function () {
        const divisionId = this.value;

        resetSelect(districtSelect, 'All Districts');
        resetSelect(upazillaSelect, 'All Upazillas');
        resetSelect(cityCorporationSelect, 'All City Corporations');
        resetSelect(cityAreaSelect, 'All City Areas');

        if (!divisionId) return;

        try {
            const districts = await fetchData(`/locations/divisions/${divisionId}/districts`);
            populateSelect(districtSelect, districts, 'All Districts');
        } catch (error) {
            console.error(error);
        }
    });

    districtSelect.addEventListener('change', async function () {
        const districtId = this.value;

        resetSelect(upazillaSelect, 'All Upazillas');
        resetSelect(cityCorporationSelect, 'All City Corporations');
        resetSelect(cityAreaSelect, 'All City Areas');

        if (!districtId) return;

        try {
            const [upazillas, cityCorporations] = await Promise.all([
                fetchData(`/locations/districts/${districtId}/upazillas`),
                fetchData('/locations/dhaka/city-corporations')
            ]);

            populateSelect(upazillaSelect, upazillas, 'All Upazillas');
            populateSelect(cityCorporationSelect, cityCorporations, 'All City Corporations');
        } catch (error) {
            console.error(error);
        }
    });

    cityCorporationSelect.addEventListener('change', async function () {
        const cityCorporationId = this.value;

        resetSelect(cityAreaSelect, 'All City Areas');

        if (!cityCorporationId) return;

        try {
            const cityAreas = await fetchData(`/locations/dhaka/city-corporations/${cityCorporationId}/areas`);
            populateSelect(cityAreaSelect, cityAreas, 'All City Areas');
        } catch (error) {
            console.error(error);
        }
    });

    locationTypeSelect.addEventListener('change', toggleLocationFields);

    toggleLocationFields();
});
</script>
@endpush
@endsection