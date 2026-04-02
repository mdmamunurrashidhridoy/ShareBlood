@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-4xl py-6 sm:py-8">
    <div class="mb-8">
        <div class="badge-soft">Request blood</div>
        <h1 class="mt-4 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">
            Create Blood Request
        </h1>
        <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600 sm:text-base">
            Share the details clearly so nearby donors can understand the urgency and respond more quickly.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-[28px] border border-red-200 bg-red-50/90 p-5 text-red-800 shadow-sm">
            <div class="text-sm font-semibold">Please fix the following issues</div>
            <ul class="mt-3 list-disc space-y-1 pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card-soft p-5 sm:p-7">
        <form method="POST" action="{{ route('blood-requests.store') }}" class="space-y-6" id="requestForm">
            @csrf

            <input type="hidden" name="location_mode" id="location_mode" value="{{ old('location_mode','upazila') }}">

            {{-- Requester --}}
            <section class="rounded-[26px] border border-rose-100 bg-rose-50/50 p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Requester Information</h2>
                        <p class="mt-1 text-sm text-slate-500">So donors know who to contact.</p>
                    </div>
                    <div class="hidden sm:block rounded-full bg-white px-3 py-1 text-xs font-semibold text-red-700 ring-1 ring-rose-100">
                        Step 1
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Requester Name</label>
                        <input
                            name="requester_name"
                            value="{{ old('requester_name', auth()->user()->name ?? '') }}"
                            required
                            class="mt-2"
                            placeholder="Full name">
                        @error('requester_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Requester Phone</label>
                        <input
                            name="requester_phone"
                            value="{{ old('requester_phone', auth()->user()->phone ?? '') }}"
                            required
                            class="mt-2"
                            placeholder="01XXXXXXXXX">
                        @error('requester_phone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            {{-- Patient --}}
            <section class="rounded-[26px] border border-slate-100 bg-white p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Patient & Blood Need</h2>
                        <p class="mt-1 text-sm text-slate-500">Add the core request details clearly.</p>
                    </div>
                    <div class="hidden sm:block rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-red-700 ring-1 ring-rose-100">
                        Step 2
                    </div>
                </div>

                <div class="mt-5">
                    <label class="block text-sm font-medium text-slate-700">Patient Name</label>
                    <input
                        name="patient_name"
                        value="{{ old('patient_name') }}"
                        required
                        class="mt-2"
                        placeholder="Patient full name">
                    @error('patient_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Blood Group</label>
                        <select name="blood_group" required class="mt-2">
                            <option value="">Select blood group</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" @selected(old('blood_group') === $bg)>{{ $bg }}</option>
                            @endforeach
                        </select>
                        @error('blood_group') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-end">
                        <label class="flex w-full items-center gap-3 rounded-2xl border border-red-200 bg-red-50/70 px-4 py-3">
                            <input
                                type="checkbox"
                                name="is_emergency"
                                value="1"
                                class="rounded border-red-300 text-red-600 focus:ring-red-500"
                                @checked(old('is_emergency'))>
                            <div>
                                <div class="text-sm font-semibold text-red-700">Emergency Request</div>
                                <div class="text-xs text-red-600/80">Mark this when blood is needed urgently.</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Needed Date</label>
                        <input
                            type="date"
                            name="needed_date"
                            value="{{ old('needed_date') }}"
                            required
                            class="mt-2">
                        @error('needed_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Quantity (bags)</label>
                        <input
                            type="number"
                            name="quantity_bags"
                            min="1"
                            max="20"
                            value="{{ old('quantity_bags') }}"
                            class="mt-2"
                            placeholder="e.g. 2">
                        @error('quantity_bags') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            {{-- Location --}}
            <section class="rounded-[26px] border border-slate-100 bg-white p-5 sm:p-6">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Location Details</h2>
                        <p class="mt-1 text-sm text-slate-500">This helps match nearby donors more accurately.</p>
                    </div>
                    <div class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">
                        Required for matching
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Division</label>
                        <select id="division_id" name="division_id" required class="mt-2">
                            <option value="">Select division</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}" @selected(old('division_id') == $division->id)>
                                    {{ $division->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('division_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">District</label>
                        <select id="district_id" name="district_id" required class="mt-2">
                            <option value="">Select district</option>
                        </select>
                        @error('district_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div id="dhakaModeWrapper" class="hidden mt-5 rounded-2xl border border-rose-100 bg-rose-50/50 p-4">
                    <label class="block text-sm font-semibold text-slate-900">Dhaka Location Type</label>
                    <p class="mt-1 text-xs leading-6 text-slate-600">
                        Choose whether the location should be selected by Thana/Upazila or by City Corporation area.
                    </p>

                    <div class="mt-4 flex flex-wrap gap-3">
                        <label class="inline-flex items-center gap-2 rounded-full border border-rose-100 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm">
                            <input type="radio" name="dhaka_mode_radio" value="upazila" class="text-red-600 focus:ring-red-500" checked>
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
                    <select id="upazila_id" name="upazilla_id" class="mt-2">
                        <option value="">Select upazila</option>
                    </select>
                    @error('upazilla_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div id="corpWrapper" class="hidden mt-5">
                    <label class="block text-sm font-medium text-slate-700">City Corporation</label>
                    <select id="city_corporation_id" name="city_corporation_id" class="mt-2">
                        <option value="">Select city corporation</option>
                    </select>
                    @error('city_corporation_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div id="cityAreaWrapper" class="hidden mt-5">
                    <label class="block text-sm font-medium text-slate-700">City Area</label>
                    <select id="city_area_id" name="city_area_id" class="mt-2">
                        <option value="">Select city area</option>
                    </select>
                    @error('city_area_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </section>

            {{-- Extra --}}
            <section class="rounded-[26px] border border-slate-100 bg-white p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Additional Details</h2>
                        <p class="mt-1 text-sm text-slate-500">Optional but helpful for donors.</p>
                    </div>
                    <div class="hidden sm:block rounded-full bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">
                        Optional
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Hospital Name</label>
                        <input
                            name="hospital_name"
                            value="{{ old('hospital_name') }}"
                            class="mt-2"
                            placeholder="e.g. Dhaka Medical College">
                        @error('hospital_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Address Line</label>
                        <input
                            name="address_line"
                            value="{{ old('address_line') }}"
                            class="mt-2"
                            placeholder="Ward, Road, House, Gate, etc.">
                        @error('address_line') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-5">
                    <label class="block text-sm font-medium text-slate-700">Note</label>
                    <textarea
                        name="note"
                        rows="4"
                        class="mt-2"
                        placeholder="Anything important donors should know...">{{ old('note') }}</textarea>
                    @error('note') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </section>

            <div class="flex flex-col gap-3 border-t border-rose-100 pt-6 sm:flex-row sm:items-center sm:justify-end">
                <a href="{{ url()->previous() }}"
                    class="btn-secondary w-full sm:w-auto">
                    Cancel
                </a>

                <button id="submitBtn" class="btn-primary w-full sm:w-auto">
                    Create Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const form = document.getElementById('requestForm');

    const divisionEl = document.getElementById('division_id');
    const districtEl = document.getElementById('district_id');

    const dhakaModeWrapper = document.getElementById('dhakaModeWrapper');

    const upazilaWrapper = document.getElementById('upazilaWrapper');
    const upazilaEl = document.getElementById('upazila_id');

    const corpWrapper = document.getElementById('corpWrapper');
    const corpEl = document.getElementById('city_corporation_id');

    const cityAreaWrapper = document.getElementById('cityAreaWrapper');
    const cityAreaEl = document.getElementById('city_area_id');

    const locationModeEl = document.getElementById('location_mode');

    const submitBtn = document.getElementById('submitBtn');

    const oldDivision = @js(old('division_id'));
    const oldDistrict = @js(old('district_id'));
    const oldUpazila = @js(old('upazilla_id'));
    const oldMode = @js(old('location_mode', 'upazila'));
    const oldCorp = @js(old('city_corporation_id'));
    const oldCityArea = @js(old('city_area_id'));

    function resetSelect(selectEl, placeholder) {
        selectEl.innerHTML = '';
        const opt = document.createElement('option');
        opt.value = '';
        opt.textContent = placeholder;
        selectEl.appendChild(opt);
        selectEl.disabled = false;
    }

    function setLoading(selectEl, text = 'Loading...') {
        selectEl.innerHTML = `<option value="">${text}</option>`;
        selectEl.disabled = true;
    }

    function setVisible(el, visible) {
        el.classList.toggle('hidden', !visible);
    }

    function isDhakaDistrictSelected() {
        const text = districtEl.options[districtEl.selectedIndex]?.textContent?.trim() || '';
        return text.toLowerCase() === 'dhaka';
    }

    async function loadDistricts(divisionId) {
        resetSelect(districtEl, 'Select District');
        resetSelect(upazilaEl, 'Select Upazila');
        resetSelect(corpEl, 'Select City Corporation');
        resetSelect(cityAreaEl, 'Select City Area');

        setVisible(dhakaModeWrapper, false);
        setVisible(upazilaWrapper, true);
        setVisible(corpWrapper, false);
        setVisible(cityAreaWrapper, false);
        locationModeEl.value = 'upazila';

        if (!divisionId) return;

        setLoading(districtEl);

        const res = await fetch(`/locations/divisions/${divisionId}/districts`, {
            headers: { 'Accept': 'application/json' }
        });

        const data = await res.json();

        resetSelect(districtEl, 'Select District');
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

        setLoading(upazilaEl);

        const res = await fetch(`/locations/districts/${districtId}/upazillas`, {
            headers: { 'Accept': 'application/json' }
        });

        const data = await res.json();

        resetSelect(upazilaEl, 'Select Upazila');
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

        const res = await fetch(`/locations/dhaka/city-corporations`, {
            headers: { 'Accept': 'application/json' }
        });

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

        setLoading(cityAreaEl);

        const res = await fetch(`/locations/dhaka/city-corporations/${corpId}/areas`, {
            headers: { 'Accept': 'application/json' }
        });

        const areas = await res.json();

        resetSelect(cityAreaEl, 'Select City Area');
        areas.forEach(a => {
            const opt = document.createElement('option');
            opt.value = a.id;
            opt.textContent = a.name;
            cityAreaEl.appendChild(opt);
        });
    }

    divisionEl.addEventListener('change', async () => {
        await loadDistricts(divisionEl.value);
    });

    districtEl.addEventListener('change', async () => {
        await loadUpazilas(districtEl.value);

        const isDhaka = isDhakaDistrictSelected();
        setVisible(dhakaModeWrapper, isDhaka);

        if (!isDhaka) {
            locationModeEl.value = 'upazila';
            setVisible(upazilaWrapper, true);
            setVisible(corpWrapper, false);
            setVisible(cityAreaWrapper, false);
            return;
        }

        locationModeEl.value = 'upazila';
        document.querySelector('input[name="dhaka_mode_radio"][value="upazila"]').checked = true;
        setVisible(upazilaWrapper, true);
        setVisible(corpWrapper, false);
        setVisible(cityAreaWrapper, false);

        resetSelect(corpEl, 'Select City Corporation');
        resetSelect(cityAreaEl, 'Select City Area');
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

    corpEl.addEventListener('change', async () => {
        await loadCityAreas(corpEl.value);
    });

    form.addEventListener('submit', function () {
        console.log('location_mode:', locationModeEl.value);
        console.log('upazilla_id:', upazilaEl.value);
        console.log('city_corporation_id:', corpEl.value);
        console.log('city_area_id:', cityAreaEl.value);

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
            submitBtn.textContent = 'Creating...';
        }
    });

    (async function init() {
        if (oldDivision) {
            divisionEl.value = oldDivision;
            await loadDistricts(oldDivision);
        } else {
            resetSelect(districtEl, 'Select District');
        }

        if (oldDistrict) {
            districtEl.value = oldDistrict;
            await loadUpazilas(oldDistrict);
        } else {
            resetSelect(upazilaEl, 'Select Upazila');
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
            locationModeEl.value = 'upazila';
            if (oldUpazila) upazilaEl.value = oldUpazila;
        }
    })();
})();
</script>
@endsection