<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Division;
use App\Models\BloodRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Notifications\NewBloodRequestNotification;
use App\Events\BloodRequestCreated;

class BloodRequestController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $requests = BloodRequest::with(['division', 'district', 'upazilla'])
            ->publicVisible()
            ->latest()
            ->paginate(12);

        return view('blood_requests.index', compact('requests'));
    }

    public function my(Request $request)
    {
        $requests = BloodRequest::with(['division', 'district', 'upazilla'])
            ->where('requester_user_id', $request->user()->id)
            ->latest()
            ->paginate(12);

        return view('blood_requests.my', [
            'requests' => $requests,
            'debug' => 'MY VIEW SHOULD SHOW THIS: ' . now(),
        ]);
    }

    public function create(Request $request)
    {
        $divisions = Division::orderBy('name')
            ->get(['id', 'name']);

        return view('blood_requests.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];

        $validated = $request->validate([
            'requester_name' => ['required', 'string', 'max:150'],
            'requester_phone' => ['required', 'string', 'max:20'],

            'patient_name' => ['required', 'string', 'max:150'],
            'blood_group' => ['required', Rule::in($bloodGroups)],

            'quantity_bags' => ['nullable', 'integer', 'min:1', 'max:20'],
            'needed_date' => ['required', 'date', 'after_or_equal:today'],
            'is_emergency' => ['nullable', 'boolean'],

            'division_id' => ['required', 'integer', 'exists:divisions,id'],
            'district_id' => ['required', 'integer', 'exists:districts,id'],

            // important for Dhaka logic
            'location_mode' => ['required', Rule::in(['upazila', 'city'])],

            // conditional rules
            'upazilla_id' => [
                Rule::requiredIf(fn() => $request->input('location_mode') === 'upazila'),
                'nullable',
                'integer',
                'exists:upazillas,id',
            ],

            'city_corporation_id' => [
                Rule::requiredIf(fn() => $request->input('location_mode') === 'city'),
                'nullable',
                'integer',
                'exists:city_corporations,id',
            ],

            // if you have city_area table + route
            'city_area_id' => [
                Rule::requiredIf(fn() => $request->input('location_mode') === 'city'),
                'nullable',
                'integer',
                'exists:city_areas,id',
            ],

            'hospital_name' => ['nullable', 'string', 'max:150'],

            'note' => ['nullable', 'string', 'max:800'],
            'expires_at' => ['nullable', 'date'],
        ]);

        // If city mode, ensure upazilla_id is null (clean DB)
        if (($validated['location_mode'] ?? 'upazila') === 'city') {
            $validated['upazilla_id'] = null;
        } else {
            $validated['city_corporation_id'] = null;
            $validated['city_area_id'] = null;
        }

        $validated['requester_user_id'] = $request->user()->id;
        $validated['is_emergency'] = (bool) $request->boolean('is_emergency');
        $validated['status'] = 'pending';

        $validated['expires_at'] = $validated['expires_at']
            ?? now()->parse($validated['needed_date'])->addDay()->endOfDay();

        $bloodRequest = BloodRequest::create($validated);

        event(new BloodRequestCreated($bloodRequest));

        return redirect()->route('blood-requests.my')->with('success', 'lood request created and eligible donors notified.');
    }

    public function cancel(BloodRequest $bloodRequest)
    {
        $this->authorize('cancel', $bloodRequest);

        $bloodRequest->update(['status' => 'cancelled']);

        return back()->with('success', 'Request cancelled');
    }

    public function complete(BloodRequest $bloodRequest)
    {
        $this->authorize('complete', $bloodRequest);

        $bloodRequest->update(['status' => 'completed']);

        return back()->with('success', 'Request marked completed.');
    }
    public function show(BloodRequest $bloodRequest)
    {
        $bloodRequest->load([
            'requester',
            'division',
            'district',
            'upazilla',
            'cityCorporation',
            'cityArea',
            'donorResponses.donor',
        ]);

        return view('blood_requests.show', compact('bloodRequest'));
    }
}
