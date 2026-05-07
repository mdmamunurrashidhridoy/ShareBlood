<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BloodRequestApiController extends Controller
{
    public function index()
    {
        $bloodRequests = BloodRequest::with(['division', 'district', 'upazilla'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => true,
            'data' => $bloodRequests
        ]);
    }

    public function show(BloodRequest $bloodRequest)
    {
        $bloodRequest->load([
            'division',
            'district',
            'upazilla',
            'cityCorporation',
            'cityArea'
        ]);

        return response()->json(
            [
                'status' => true,
                'data' => $bloodRequest
            ]
        );
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

            'location_mode' => ['required', Rule::in(['upazila', 'city'])],

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
            'city_area_id' => [
                Rule::requiredIf(fn() => $request->input('location_mode') === 'city'),
                'nullable',
                'integer',
                'exists:city_areas,id',
            ],

            'hospital_name' => ['nullable', 'string', 'max:150'],
            'note' => ['nullable', 'string', 'max:800'],

        ]);

        if ($validated['location_mode'] === 'city') {
            $validated['upazilla_id'] = null;
        } else {
            $validated['city_corporation_id'] = null;
            $validated['city_area_id'] = null;
        }

        $validated['quantity_bags'] = $validated['quantity_bags'] ?? 1;
        $validated['is_emergency'] = $request->boolean('is_emergency');
        $validated['status'] = 'pending';

        // temporary for testing without login
        $validated['requester_user_id'] = $request->user()->id;

        $validated['expires_at'] = now()
            ->parse($validated['needed_date'])
            ->addDay()
            ->endOfDay();

        $bloodRequest = BloodRequest::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Blood request created successfully',
            'data' => $bloodRequest,
        ], 201);

    }


}
