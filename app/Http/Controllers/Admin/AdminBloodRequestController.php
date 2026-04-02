<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminBloodRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = BloodRequest::with([
            'requester',
            'division',
            'district',
            'upazilla',
            'cityCorporation',
            'cityArea',
        ])->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('patient_name', 'like', "%{$search}%")
                    ->orWhere('requester_name', 'like', "%{$search}%")
                    ->orWhere('requester_phone', 'like', "%{$search}%")
                    ->orWhere('hospital_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_emergency')) {
            $query->where('is_emergency', $request->is_emergency === '1');
        }

        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        $bloodRequests = $query->paginate(12)->withQueryString();

        $districts = District::orderBy('name')->get();

        return view('admin.blood-requests.index', compact('bloodRequests', 'districts'));
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
        ]);

        return view('admin.blood-requests.show', compact('bloodRequest'));
    }

    public function updateStatus(Request $request, BloodRequest $bloodRequest)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(BloodRequest::STATUSES)],
        ]);

        $bloodRequest->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Blood request status updated successfully.');
    }

    public function destroy(BloodRequest $bloodRequest)
    {
        $bloodRequest->delete();

        return redirect()
            ->route('admin.blood-requests.index')
            ->with('success', 'Blood request deleted successfully.');
    }
}
