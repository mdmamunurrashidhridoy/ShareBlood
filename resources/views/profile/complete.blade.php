@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-4xl py-6 sm:py-8">
    <div class="mb-8">
        <div class="badge-soft">Profile setup</div>
        <h1 class="mt-4 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">
            Complete Your Profile
        </h1>
        <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600 sm:text-base">
            Add your contact details, blood group, and location so requests and donor matching can work more accurately.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-[28px] border border-red-200 bg-red-50/90 p-5 text-red-800 shadow-sm">
            <div class="text-sm font-semibold">Please fix the following issues</div>
            <ul class="mt-3 list-disc space-y-1 pl-5 text-sm">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card-soft p-5 sm:p-7">
        <form method="POST" action="{{ route('profile.complete.store') }}" class="space-y-6">
            @csrf

            <input type="hidden" name="location_mode" id="location_mode" value="{{ old('location_mode','upazila') }}">

            {{-- Basic Info --}}
            <section class="rounded-[26px] border border-rose-100 bg-rose-50/50 p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Basic Information</h2>
                        <p class="mt-1 text-sm text-slate-500">The essentials people may need to contact and match you.</p>
                    </div>

                    <div class="hidden sm:block rounded-full bg-white px-3 py-1 text-xs font-semibold text-red-700 ring-1 ring-rose-100">
                        Step 1
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Phone</label>
                        <input
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            class="mt-2"
                            placeholder="01XXXXXXXXX"
                            required>
                        @error('phone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Blood Group</label>
                        <select name="blood_group" class="mt-2" required>
                            <option value="">Select Blood Group</option>
                            @php
                                $groups = ['A+','A-','B+','B-','O+','O-','AB+','AB-'];
                                $sel = old('blood_group', $user->blood_group);
                            @endphp
                            @foreach($groups as $g)
                                <option value="{{ $g }}" @selected($sel === $g)>{{ $g }}</option>
                            @endforeach
                        </select>
                        @error('blood_group') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            {{-- Location --}}
            <section class="rounded-[26px] border border-slate-100 bg-white p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Location</h2>
                        <p class="mt-1 text-sm text-slate-500">Used for donor discovery and blood request matching.</p>
                    </div>

                    <div class="hidden sm:block rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">
                        Required
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Division</label>
                        <select id="division" name="division_id" class="mt-2" required>
                            <option value="">Select Division</option>
                            @foreach($divisions as $d)
                                <option value="{{ $d->id }}" @selected(old('division_id') == $d->id)>{{ $d->name }}</option>
                            @endforeach
                        </select>
                        @error('division_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">District</label>
                        <select id="district" name="district_id" class="mt-2" required>
                            <option value="">Select District</option>
                        </select>
                        @error('district_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div id="dhakaModeWrapper" class="hidden mt-5 rounded-2xl border border-rose-100 bg-rose-50/50 p-4">
                    <label class="block text-sm font-semibold text-slate-900">Dhaka Location Type</label>
                    <p class="mt-1 text-xs leading-6 text-slate-600">
                        Choose whether your Dhaka location should use Thana/Upazila or City Corporation areas.
                    </p>

                    <div class="mt-4 flex flex-wrap gap-3">
                        <label class="inline-flex items-center gap-2 rounded-full border border-rose-100 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm">
                            <input type="radio" name="dhaka_mode_radio" value="upazila" checked class="text-red-600 focus:ring-red-500">
                            <span>Thana / Upazila</span>
                        </label>

                        <label class="inline-flex items-center gap-2 rounded-full border border-rose-100 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm">
                            <input type="radio" name="dhaka_mode_radio" value="city" class="text-red-600 focus:ring-red-500">
                            <span>City Corporation</span>
                        </label>
                    </div>
                </div>

                <div id="upazilaWrapper" class="mt-5">
                    <label class="block text-sm font-medium text-slate-700">Upazila / Thana</label>
                    <select id="upazila" name="upazilla_id" class="mt-2">
                        <option value="">Select Upazila</option>
                    </select>
                    @error('upazilla_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div id="corpWrapper" class="hidden mt-5">
                    <label class="block text-sm font-medium text-slate-700">City Corporation</label>
                    <select id="city_corporation" name="city_corporation_id" class="mt-2">
                        <option value="">Select City Corporation</option>
                    </select>
                    @error('city_corporation_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div id="cityAreaWrapper" class="hidden mt-5">
                    <label class="block text-sm font-medium text-slate-700">City Area</label>
                    <select id="city_area" name="city_area_id" class="mt-2">
                        <option value="">Select City Area</option>
                    </select>
                    @error('city_area_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </section>

            {{-- Optional --}}
            <section class="rounded-[26px] border border-slate-100 bg-white p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Additional Details</h2>
                        <p class="mt-1 text-sm text-slate-500">Helpful information you may optionally include.</p>
                    </div>

                    <div class="hidden sm:block rounded-full bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
                        Optional
                    </div>
                </div>

                <div class="mt-5 space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Address</label>
                        <input
                            name="address_line"
                            value="{{ old('address_line', $user->address_line) }}"
                            class="mt-2"
                            placeholder="House, road, area, etc.">
                        @error('address_line') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Medical History</label>
                        <textarea
                            name="medical_history"
                            rows="4"
                            class="mt-2"
                            placeholder="Optional medical information relevant to donation">{{ old('medical_history', $user->medical_history) }}</textarea>
                        @error('medical_history') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="rounded-2xl border border-rose-100 bg-rose-50/50 p-4">
                        <label class="flex items-start gap-3">
                            <input
                                type="checkbox"
                                id="become_donor"
                                name="become_donor"
                                value="1"
                                class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500"
                                @checked(old('become_donor'))>
                            <div>
                                <div class="font-medium text-slate-900">I want to be a donor</div>
                                <div class="mt-1 text-sm leading-6 text-slate-600">
                                    Enable this if you want your profile to be considered for donor matching.
                                </div>
                            </div>
                        </label>

                        <div id="donorDateWrapper" class="hidden mt-4">
                            <label class="block text-sm font-medium text-slate-700">Last Donation Date</label>
                            <input
                                type="date"
                                name="last_donate_date"
                                value="{{ old('last_donate_date') }}"
                                class="mt-2">
                            @error('last_donate_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </section>

            <div class="flex flex-col-reverse gap-3 border-t border-rose-100 pt-6 sm:flex-row sm:items-center sm:justify-end">
                <a href="{{ route('dashboard') }}" class="btn-secondary w-full sm:w-auto">
                    Cancel
                </a>

                <button type="submit" class="btn-primary w-full sm:w-auto">
                    Save Profile
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    const divisionEl = document.getElementById('division');
    const districtEl = document.getElementById('district');
    const upazilaEl = document.getElementById('upazila');

    const dhakaModeWrapper = document.getElementById('dhakaModeWrapper');
    const upazilaWrapper = document.getElementById('upazilaWrapper');

    const corpWrapper = document.getElementById('corpWrapper');
    const corpEl = document.getElementById('city_corporation');

    const cityAreaWrapper = document.getElementById('cityAreaWrapper');
    const cityAreaEl = document.getElementById('city_area');

    const locationModeEl = document.getElementById('location_mode');

    const becomeDonorEl = document.getElementById('become_donor');
    const donorDateWrapper = document.getElementById('donorDateWrapper');

    function resetSelect(selectEl, placeholder) {
        selectEl.innerHTML = '';
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = placeholder;
        selectEl.appendChild(opt);
    }

    function setVisible(el, visible) {
        el.classList.toggle('hidden', !visible);
    }

    async function loadDistricts(divisionId) {
        resetSelect(districtEl, 'Select District');
        resetSelect(upazilaEl, 'Select Upazila');

        setVisible(dhakaModeWrapper, false);
        setVisible(corpWrapper, false);
        setVisible(cityAreaWrapper, false);
        setVisible(upazilaWrapper, true);
        locationModeEl.value = 'upazila';

        if (!divisionId) return;

        const res = await fetch(`/locations/divisions/${divisionId}/districts`);
        const data = await res.json();
        data.forEach(d => {
            const opt = document.createElement('option');
            opt.value = d.id;
            opt.textContent = d.name;
            districtEl.appendChild(opt);
        });
    }

    async function loadUpazilas(districtId) {
        resetSelect(upazilaEl, 'Select Upazila');
        if (!districtId) return;

        const res = await fetch(`/locations/districts/${districtId}/upazillas`);
        const data = await res.json();
        data.forEach(u => {
            const opt = document.createElement('option');
            opt.value = u.id;
            opt.textContent = u.name;
            upazilaEl.appendChild(opt);
        });
    }

    async function loadDhakaCorps() {
        resetSelect(corpEl, 'Select City Corporation');
        resetSelect(cityAreaEl, 'Select City Area');

        const res = await fetch(`/locations/dhaka/city-corporations`);
        const corps = await res.json();

        corps.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.name;
            corpEl.appendChild(opt);
        });
    }

    async function loadCityAreas(corpId) {
        resetSelect(cityAreaEl, 'Select City Area');
        if (!corpId) return;

        const res = await fetch(`/locations/dhaka/city-corporations/${corpId}/areas`);
        const areas = await res.json();

        areas.forEach(a => {
            const opt = document.createElement('option');
            opt.value = a.id;
            opt.textContent = a.name;
            cityAreaEl.appendChild(opt);
        });
    }

    function isDhakaDistrictSelected() {
        const text = districtEl.options[districtEl.selectedIndex]?.textContent?.trim();
        return text === 'Dhaka';
    }

    divisionEl?.addEventListener('change', () => loadDistricts(divisionEl.value));

    districtEl?.addEventListener('change', async () => {
        await loadUpazilas(districtEl.value);

        const isDhaka = isDhakaDistrictSelected();
        setVisible(dhakaModeWrapper, isDhaka);

        if (!isDhaka) {
            setVisible(upazilaWrapper, true);
            setVisible(corpWrapper, false);
            setVisible(cityAreaWrapper, false);
            locationModeEl.value = 'upazila';
            return;
        }

        locationModeEl.value = 'upazila';
        document.querySelector('input[name="dhaka_mode_radio"][value="upazila"]').checked = true;
        setVisible(upazilaWrapper, true);
        setVisible(corpWrapper, false);
        setVisible(cityAreaWrapper, false);
    });

    document.querySelectorAll('input[name="dhaka_mode_radio"]').forEach(r => {
        r.addEventListener('change', async (e) => {
            const mode = e.target.value;
            locationModeEl.value = mode;

            if (mode === 'city') {
                setVisible(upazilaWrapper, false);
                setVisible(corpWrapper, true);
                setVisible(cityAreaWrapper, true);
                await loadDhakaCorps();
            } else {
                setVisible(upazilaWrapper, true);
                setVisible(corpWrapper, false);
                setVisible(cityAreaWrapper, false);
                resetSelect(corpEl, 'Select City Corporation');
                resetSelect(cityAreaEl, 'Select City Area');
            }
        });
    });

    corpEl?.addEventListener('change', () => loadCityAreas(corpEl.value));

    function toggleDonorDate() {
        setVisible(donorDateWrapper, becomeDonorEl.checked);
    }
    becomeDonorEl?.addEventListener('change', toggleDonorDate);
    toggleDonorDate();

    const oldDivision = "{{ old('division_id') }}";
    const oldDistrict = "{{ old('district_id') }}";
    const oldUpazila = "{{ old('upazilla_id') }}";
    const oldMode = "{{ old('location_mode','upazila') }}";
    const oldCorp = "{{ old('city_corporation_id') }}";
    const oldCityArea = "{{ old('city_area_id') }}";

    (async function init() {
        if (oldDivision) {
            divisionEl.value = oldDivision;
            await loadDistricts(oldDivision);
        }
        if (oldDistrict) {
            districtEl.value = oldDistrict;
            await loadUpazilas(oldDistrict);
        }

        const isDhaka = isDhakaDistrictSelected();
        setVisible(dhakaModeWrapper, isDhaka);

        if (isDhaka && oldMode === 'city') {
            locationModeEl.value = 'city';
            document.querySelector('input[name="dhaka_mode_radio"][value="city"]').checked = true;

            setVisible(upazilaWrapper, false);
            setVisible(corpWrapper, true);
            setVisible(cityAreaWrapper, true);

            await loadDhakaCorps();
            if (oldCorp) {
                corpEl.value = oldCorp;
                await loadCityAreas(oldCorp);
            }
            if (oldCityArea) cityAreaEl.value = oldCityArea;
        } else {
            if (oldUpazila) upazilaEl.value = oldUpazila;
        }
    })();
})();
</script>
@endsection