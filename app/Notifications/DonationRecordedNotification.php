<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\BloodRequest;
use App\Models\BloodRequestDonor;

class DonationRecordedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected BloodRequest $bloodRequest,
        protected BloodRequestDonor $response
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }




    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $bags = $this->response->bags_donated ?: 1;
        return [
            'type' => 'donation_recorded',
            'title' => 'Your donation was recorded',
            'message' => "Thank you. Your donation for {$this->bloodRequest->patient_name} was recorded successfully ({$bags} bag(s)).",
            'blood_request_id' => $this->bloodRequest->id,
            'blood_request_donor_id' => $this->response->id,
            'patient_name' => $this->bloodRequest->patient_name,
            'bags_donated' => $bags,
            'status' => $this->response->status,
            'action_url' => route('blood-requests.show', $this->bloodRequest),
        ];
    }
}
