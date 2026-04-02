<?php

namespace App\Http\Controllers;

use App\Models\CityCorporation;
use App\Models\District;
use App\Models\Division;
use App\Models\DonorProfile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\BloodRequest;
use App\Models\BloodRequestDonor;


class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load([
            'division',
            'district',
            'upazilla',
            'cityCorporation',
            'cityArea',
            'donorProfile',
        ]);

        $requestStats = [
            'total' => $user->bloodRequestsMade()->count(),
            'pending' => $user->bloodRequestsMade()->where('status', 'pending')->count(),
            'accepted' => $user->bloodRequestsMade()->where('status', 'accepted')->count(),
            'completed' => $user->bloodRequestsMade()->where('status', 'completed')->count(),
            'cancelled' => $user->bloodRequestsMade()->where('status', 'cancelled')->count(),
            'expired' => $user->bloodRequestsMade()->where('status', 'expired')->count(),
        ];

        $donationStats = [
            'responses_total' => $user->bloodRequestResponses()->count(),
            'interested' => $user->bloodRequestResponses()->where('status', BloodRequestDonor::STATUS_INTERESTED)->count(),
            'selected' => $user->bloodRequestResponses()->where('status', BloodRequestDonor::STATUS_SELECTED)->count(),
            'donated' => $user->bloodRequestResponses()->where('status', BloodRequestDonor::STATUS_DONATED)->count(),
            'rejected' => $user->bloodRequestResponses()->where('status', BloodRequestDonor::STATUS_REJECTED)->count(),
            'cancelled' => $user->bloodRequestResponses()->where('status', BloodRequestDonor::STATUS_CANCELLED)->count(),
            'bags_donated' => (int) $user->bloodRequestResponses()
                ->where('status', BloodRequestDonor::STATUS_DONATED)
                ->sum('bags_donated'),
            'last_donated_at' => $user->bloodRequestResponses()
                ->where('status', BloodRequestDonor::STATUS_DONATED)
                ->latest('donated_at')
                ->value('donated_at'),
        ];

        $recentRequests = $user->bloodRequestsMade()
            ->latest()
            ->take(5)
            ->get();

        $recentDonationActivities = $user->bloodRequestResponses()
            ->with(['bloodRequest'])
            ->latest()
            ->take(5)
            ->get();

        return view('profile.show', compact(
            'user',
            'requestStats',
            'donationStats',
            'recentRequests',
            'recentDonationActivities'
        ));
    }
    public function completeForm(Request $request)
    {
        $user = $request->user();

        if ($this->isProfileComplete($user)) {
            return redirect()->route('dashboard');
        }

        $divisions = Division::orderBy('name')->get(['id', 'name']);

        return view('profile.complete', compact('user', 'divisions'));
    }

    public function completeStore(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'phone' => [
                'required',
                'string',
                'max:11',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'blood_group' => ['required', Rule::in(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'])],

            'division_id' => ['required', 'exists:divisions,id'],
            'district_id' => ['required', 'exists:districts,id'],

            // Normal flow:
            'upazilla_id' => ['nullable', 'exists:upazillas,id'],

            // Dhaka city flow:
            'city_corporation_id' => ['nullable', 'exists:city_corporations,id'],
            'city_area_id' => ['nullable', 'exists:city_areas,id'],

            'address_line' => ['nullable', 'string', 'max:255'],
            'medical_history' => ['nullable', 'string'],

            'become_donor' => ['nullable', 'boolean'],
            'last_donate_date' => ['nullable', 'date', 'required_if:become_donor,1'],
        ]);

        // Enforce one of the two location paths
        $district = District::find($validated['district_id']);

        $isDhakaDistrict = $district && $district->name === 'Dhaka';

        // Your form should send a flag like: location_mode = 'city' or 'upazila'
        $locationMode = $request->input('location_mode'); // 'city' | 'upazila' | null

        if ($isDhakaDistrict && $locationMode === 'city') {
            if (empty($validated['city_corporation_id']) || empty($validated['city_area_id'])) {
                return back()->withErrors([
                    'city_area_id' => 'City Area is required for City Corporation.',
                ])->withInput();
            }

            // Clear upazila to avoid mixed data
            $validated['upazilla_id'] = null;
        } else {
            if (empty($validated['upazilla_id'])) {
                return back()->withErrors([
                    'upazilla_id' => 'Upazila/Thana is required.',
                ])->withInput();
            }

            // Clear city fields
            $validated['city_corporation_id'] = null;
            $validated['city_area_id'] = null;
        }

        $user->fill([
            'phone' => $validated['phone'],
            'blood_group' => $validated['blood_group'],
            'division_id' => $validated['division_id'],
            'district_id' => $validated['district_id'],

            'upazilla_id' => $validated['upazilla_id'],
            'city_corporation_id' => $validated['city_corporation_id'],
            'city_area_id' => $validated['city_area_id'],

            'address_line' => $validated['address_line'] ?? null,
            'medical_history' => $validated['medical_history'] ?? null,
        ]);

        $user->save();

        $wantsDonor = (bool) ($validated['become_donor'] ?? false);

        if ($wantsDonor) {
            $donor = DonorProfile::firstOrNew(['user_id' => $user->id]);

            $donor->is_available = true;
            $donor->last_donate_date = $validated['last_donate_date'] ?? null;

            if (!empty($donor->last_donate_date)) {
                $donor->next_eligible_date = Carbon::parse($donor->last_donate_date)
                    ->addDays(90)
                    ->toDateString();
            } else {
                $donor->next_eligible_date = null;
            }

            $donor->save();
        }

        return redirect()
            ->route('dashboard')
            ->with('status', 'Profile Completed Successfully!');
    }


    // Districts by division (used by your division->district dropdown)
    public function districtsByDivision(Division $division)
    {
        $district = District::where('division_id', $division->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($district);
    }



    // Upazillas by district (if you need it)
    public function upazillasByDistrict(District $district)
    {
        return response()->json($district->upazillas()
            ->orderBy('name')
            ->get(['id', 'district_id', 'name']));
    }

    // Dhaka city corporations
    public function dhakaCityCorporation()
    {
        $dhaka = District::where('name', 'Dhaka')->firstOrFail();

        return response()->json(CityCorporation::where('district_id', $dhaka->id)
            ->orderBy('name')
            ->get(['id', 'district_id', 'name']));
    }

    public function areasByCityCorporation(CityCorporation $cityCorporation)
    {
        return response()->json(
            $cityCorporation->cityareas()
                ->orderBy('name')
                ->get(['id', 'city_corporation_id', 'name'])
        );
    }

    private function isProfileComplete($user): bool
    {
        return !empty($user->blood_group) && (
            !empty($user->upazilla_id) || !empty($user->city_area_id));
    }

    public function edit(Request $request)
    {
        $user = $request->user();

        $divisions = Division::orderBy('name')->get(['id', 'name']);

        $districts = collect();
        $upazillas = collect();
        $cityCorporations = collect();
        $cityAreas = collect();

        if ($user->division_id) {
            $districts = District::where('division_id', $user->division_id)
                ->orderBy('name')
                ->get(['id', 'division_id', 'name']);
        }

        if ($user->district_id) {
            $district = District::find($user->district_id);

            if ($district) {
                $upazillas = $district->upazillas()
                    ->orderBy('name')
                    ->get(['id', 'district_id', 'name']);

                if ($district->name === 'Dhaka') {
                    $cityCorporations = CityCorporation::where('district_id', $district->id)
                        ->orderBy('name')
                        ->get(['id', 'district_id', 'name']);
                }
            }
        }

        if ($user->city_corporation_id) {
            $cityCorporation = CityCorporation::find($user->city_corporation_id);

            if ($cityCorporation) {
                $cityAreas = $cityCorporation->cityareas()
                    ->orderBy('name')
                    ->get(['id', 'city_corporation_id', 'name']);
            }
        }

        $donorProfile = DonorProfile::where('user_id', $user->id)->first();

        $locationMode = 'upazila';

        if (!empty($user->city_corporation_id) || !empty($user->city_area_id)) {
            $locationMode = 'city';
        }

        return view('profile.edit', compact(
            'user',
            'divisions',
            'districts',
            'upazillas',
            'cityCorporations',
            'cityAreas',
            'donorProfile',
            'locationMode'
        ));
    }

    public function blocked()
    {
        $user = auth()->user();

        if (!$user || !$user->is_blocked) {
            return redirect()->route('dashboard');
        }

        return view('profile.blocked');
    }
}
