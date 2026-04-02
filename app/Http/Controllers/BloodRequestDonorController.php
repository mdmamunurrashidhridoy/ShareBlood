<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\BloodRequestDonor;
use App\Notifications\DonationRecordedNotification;
use App\Notifications\DonorInterestedNotification;
use App\Notifications\DonorRejectedNotification;
use App\Notifications\DonorResponseCancelledNotification;
use App\Notifications\DonorSelectedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BloodRequestDonorController extends Controller
{
    public function store(BloodRequest $bloodRequest): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user, 403);

        if ($bloodRequest->requester_user_id === $user->id) {
            return back()->with('error', 'You cannot respond to your own blood request.');
        }

        if (in_array($bloodRequest->status, ['completed', 'cancelled', 'expired'], true)) {
            return back()->with('error', 'This blood request is no longer open.');
        }

        if ($bloodRequest->expires_at && now()->greaterThan($bloodRequest->expires_at)) {
            return back()->with('error', 'This blood request has expired.');
        }

        $existingResponse = BloodRequestDonor::where('blood_request_id', $bloodRequest->id)
            ->where('donor_user_id', $user->id)
            ->first();

        if ($existingResponse) {
            if ($existingResponse->status === BloodRequestDonor::STATUS_CANCELLED) {
                $existingResponse->update([
                    'status' => BloodRequestDonor::STATUS_INTERESTED,
                    'responded_at' => now(),
                    'cancelled_at' => null,
                ]);

                if ($bloodRequest->requester) {
                    $bloodRequest->requester->notify(
                        new DonorInterestedNotification($bloodRequest, $existingResponse->fresh('donor'))
                    );
                }

                return back()->with('success', 'Your donor response has been re-submitted.');
            }

            return back()->with('error', 'You have already responded to this blood request.');
        }

        $response = BloodRequestDonor::create([
            'blood_request_id' => $bloodRequest->id,
            'donor_user_id' => $user->id,
            'status' => BloodRequestDonor::STATUS_INTERESTED,
            'responded_at' => now(),
        ]);

        $response->load('donor');

        if ($bloodRequest->requester) {
            $bloodRequest->requester->notify(
                new DonorInterestedNotification($bloodRequest, $response)
            );
        }

        return back()->with('success', 'Your interest has been submitted successfully.');
    }

    public function select(BloodRequest $bloodRequest, BloodRequestDonor $response): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user, 403);

        if ($bloodRequest->requester_user_id !== $user->id) {
            abort(403);
        }

        if ($response->blood_request_id !== $bloodRequest->id) {
            abort(404);
        }

        if (in_array($bloodRequest->status, ['completed', 'cancelled', 'expired'], true)) {
            return back()->with('error', 'This blood request is no longer open for donor selection.');
        }

        if ($response->status === BloodRequestDonor::STATUS_DONATED) {
            return back()->with('error', 'This donor is already marked as donated.');
        }

        if ($response->status === BloodRequestDonor::STATUS_SELECTED) {
            return back()->with('success', 'This donor is already selected.');
        }

        if (
            in_array($response->status, [
                BloodRequestDonor::STATUS_REJECTED,
                BloodRequestDonor::STATUS_CANCELLED,
            ], true)
        ) {
            return back()->with('error', 'This donor response cannot be selected now.');
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

        $response->load('donor');

        if ($response->donor) {
            $response->donor->notify(
                new DonorSelectedNotification($bloodRequest, $response)
            );
        }

        return back()->with('success', 'Donor selected successfully.');
    }

    public function reject(BloodRequest $bloodRequest, BloodRequestDonor $response): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user, 403);

        if ($bloodRequest->requester_user_id !== $user->id) {
            abort(403);
        }

        if ($response->blood_request_id !== $bloodRequest->id) {
            abort(404);
        }

        if ($response->status === BloodRequestDonor::STATUS_DONATED) {
            return back()->with('error', 'A donated response cannot be rejected.');
        }

        $response->update([
            'status' => BloodRequestDonor::STATUS_REJECTED,
            'rejected_at' => now(),
            'selected_at' => null,
        ]);

        $this->syncRequestStatus($bloodRequest);

        $response->load('donor');

        if ($response->donor) {
            $response->donor->notify(
                new DonorRejectedNotification($bloodRequest, $response)
            );
        }

        return back()->with('success', 'Donor rejected successfully.');
    }

    public function markDonated(Request $request, BloodRequest $bloodRequest, BloodRequestDonor $response): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user, 403);

        if ($bloodRequest->requester_user_id !== $user->id) {
            abort(403);
        }

        if ($response->blood_request_id !== $bloodRequest->id) {
            abort(404);
        }

        if (in_array($bloodRequest->status, ['cancelled', 'expired'], true)) {
            return back()->with('error', 'This blood request is closed.');
        }

        $validated = $request->validate([
            'bags_donated' => ['nullable', 'integer', 'min:1', 'max:1'],
            'note' => ['nullable', 'string', 'max:1000'],
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

        $response->load('donor');

        if ($response->donor) {
            $response->donor->notify(
                new DonationRecordedNotification($bloodRequest, $response)
            );
        }

        return back()->with('success', 'Donation recorded successfully.');
    }

    public function cancelResponse(BloodRequest $bloodRequest, BloodRequestDonor $response): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user, 403);

        if ($response->blood_request_id !== $bloodRequest->id) {
            abort(404);
        }

        if ($response->donor_user_id !== $user->id) {
            abort(403);
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
            $bloodRequest->update([
                'status' => 'completed',
            ]);

            return;
        }

        if ($selectedCount > 0 || $donatedBags > 0) {
            $bloodRequest->update([
                'status' => 'accepted',
            ]);

            return;
        }

        if (!in_array($bloodRequest->status, ['cancelled', 'expired'], true)) {
            $bloodRequest->update([
                'status' => 'pending',
            ]);
        }
    }
}
