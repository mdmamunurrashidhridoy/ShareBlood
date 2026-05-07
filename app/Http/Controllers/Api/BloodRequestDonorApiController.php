<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodRequest;
use app\Models\BloodRequestDonor;
use App\Models\User;


class BloodRequestDonorApiController extends Controller
{
    protected function syncRequestStatus(BloodRequest $bloodRequest): void
    {
        $bloodRequest->refresh();

        $donatedBags = (int) $bloodRequest->donorResponses()
            ->where('status', BloodRequestDonor::STATUS_DONATED)
            ->sum('bags_donated');

        $selectedCount = (int) $bloodRequest->donorResponses()
            ->where('status', BloodRequestDonor::STATUS_SELECTED)
            ->count();

        $neededBags = $bloodRequest->quantity_bags ?: 1;

        if ($donatedBags >= $neededBags) {
            $bloodRequest->update(['status' => 'completed']);
            return;
        }

        if ($selectedCount > 0 || $donatedBags > 0) {
            $bloodRequest->update(['status' => 'accepted']);
            return;
        }

        if (!in_array($bloodRequest->status, ['cancelled', 'expired'], true)) {
            $bloodRequest->update(['status' => 'pending']);
        }
    }
    public function store(Request $request, BloodRequest $bloodRequest)
    {
        $user = $request->user();

        if ($bloodRequest->requester_user_id === $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'You cannot respond to your own blood request.'
            ], 403);
        }

        if (in_array($bloodRequest->status, ['completed', 'cancelled', 'expired'], true)) {
            return response()->json([
                'status' => false,
                'message' => 'You cannot respond to your own blood request.'
            ], 422);
        }

        if ($bloodRequest->expires_at && now()->greaterThan($bloodRequest->expires_at)) {
            return response()->json([
                'status' => false,
                'message' => 'This blood request has expired.'
            ], 422);
        }

        $existingResponse = BloodRequestDonor::where(
            'blood_request_id',
            $bloodRequest->id
        )->where('donor_user_id', $user->id)
            ->first();

        if ($existingResponse) {
            return response()->json([
                'status' => false,
                'message' => 'You have already responded to this blood request.'
            ], 409);
        }

        $response = BloodRequestDonor::create([
            'blood_request_id' => $bloodRequest->id,
            'donor_user_id' => $user->id,
            'status' => BloodRequestDonor::STATUS_INTERESTED,
            'responded_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Your interest has been submitted successfully',
            'data' => $response
        ], 201);

    }

    public function select(Request $request, BloodRequest $bloodRequest, BloodRequestDonor $response)
    {
        $user = $request->user();

        if ($bloodRequest->requester_user_id != $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'Unautorized action'
            ], 403);
        }

        if ($response->blood_request_id !== $bloodRequest->id) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid response'
            ]);
        }

        if (in_array($bloodRequest->status, ['completed', 'cancelled', 'expired'], true)) {
            return response()->json([
                'status' => false,
                'message' => 'This blood request is not open.'
            ]);
        }

        if ($response->status === BloodRequestDonor::STATUS_DONATED) {
            return response()->json([
                'status' => false,
                'message' => 'Already donated.'
            ], 422);
        }

        if ($response->status === BloodRequestDonor::STATUS_SELECTED) {
            return response()->json([
                'status' => true,
                'message' => 'Already selected.'
            ]);
        }

        $response->update([
            'status' => BloodRequestDonor::STATUS_SELECTED,
            'selected_at' => now(),
            'rejected_at' => null,
        ]);

        if ($bloodRequest->status === 'pending') {
            $bloodRequest->update([
                'status' => 'accepted',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Donor selected successfully.'
        ]);
    }

    public function reject(Request $request, BloodRequest $bloodRequest, BloodRequestDonor $response)
    {
        $user = $request->user();

        if ($bloodRequest->requester_user_id !== $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        if ($response->blood_request_id !== $bloodRequest->id) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid response.'
            ], 404);
        }

        if ($response->status === BloodRequestDonor::STATUS_DONATED) {
            return response()->json([
                'status' => false,
                'message' => 'A donated response cannot be rejected.'
            ], 422);
        }

        $response->update([
            'status' => BloodRequestDonor::STATUS_REJECTED,
            'rejected_at' => now(),
            'selected_at' => null,
        ]);

        $this->syncRequestStatus($bloodRequest);

        return response()->json([
            'status' => true,
            'message' => 'Donor rejected successfully.'
        ]);
    }

    public function markDonated(Request $request, BloodRequest $bloodRequest, BloodRequestDonor $response)
    {
        $user = $request->user();

        if ($bloodRequest->requester_user_id !== $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        if ($response->blood_request_id !== $bloodRequest->id) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid response'
            ], 404);
        }

        if (in_array($bloodRequest->status, ['cancelled', 'expired'], true)) {
            return response()->json([
                'status' => false,
                'message' => 'This blood request in closed'
            ], 422);
        }

        $validated = $request->validate([
            'bags_donated' => ['nullable', 'integer', 'min:1', 'max:1'],
            'note' => ['nullable', 'string', 'max:100']
        ]);

        $response->update([
            'status' => BloodRequestDonor::STATUS_DONATED,
            'donated_at' => now(),
            'bags_donated' => $validated['bags_donated'] ?? 1,
            'note' => $validated['note'] ?? null,
            'confirmed_by_user_id' => $user->id,
            'selected_at' => $response->selected_at ?? now(),
            'rejected_at' => null,
            'cancelled_at' => null,
        ]);

        $this->syncRequestStatus($bloodRequest);

        return response()->json([
            'status' => true,
            'message' => 'Donation recorded successfully.',
            'data' => $response->fresh(),
        ]);
    }

    public function cancelResponse(Request $request, BloodRequest $bloodRequest, BloodRequestDonor $response)
    {
        $user = $request->user();

        if ($bloodRequest->requester_user_id !== $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        if ($response->blood_request_id !== $bloodRequest->id) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid response'
            ], 404);
        }

        if ($response->status === BloodRequestDonor::STATUS_DONATED) {
            return back()->with('error', 'A donated response cannot be cancelled.');
        }

        $response->update([
            'status' => BloodRequestDonor::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'selected_at' => null,
        ]);

        $this->syncRequestStatus($bloodRequest);

        $response->load('donor');

        if ($bloodRequest->requester) {
            $bloodRequest->requester->notify(
                new DonorResponseCancelledNotification($bloodRequest, $response)
            );
        }

        return back()->with('success', 'Your donor response has been cancelled.');
    }

}