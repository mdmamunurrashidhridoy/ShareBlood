<?php

namespace App\Notifications;

use App\Models\BloodRequest;
use App\Models\User;
use App\Support\BloodCompatibility;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBloodRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        public BloodRequest $bloodRequest,
        public ?User $matchedUser = null
    ) {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $donorBloodGroup = $this->matchedUser?->blood_group ?? $notifiable->blood_group ?? null;
        $requestBloodGroup = $this->bloodRequest->blood_group;

        $isExactMatch = BloodCompatibility::isExactMatch($donorBloodGroup, $requestBloodGroup);
        $isCompatibleMatch = BloodCompatibility::canDonateTo($donorBloodGroup, $requestBloodGroup);

        return [
            'blood_request_id' => $this->bloodRequest->id,
            'patient_name' => $this->bloodRequest->patient_name,
            'requested_blood_group' => $requestBloodGroup,
            'donor_blood_group' => $donorBloodGroup,
            'needed_date' => $this->bloodRequest->needed_date?->toDateString(),
            'requester_name' => $this->bloodRequest->requester_name,
            'requester_phone' => $this->bloodRequest->requester_phone,
            'district_id' => $this->bloodRequest->district_id,
            'city_corporation_id' => $this->bloodRequest->city_corporation_id,
            'city_area_id' => $this->bloodRequest->city_area_id,
            'is_exact_match' => $isExactMatch,
            'is_compatible_match' => $isCompatibleMatch,
            'message' => $isExactMatch
                ? 'A new blood request matches your blood group.'
                : 'A new blood request is compatible with your blood group.',
        ];
    }
}
