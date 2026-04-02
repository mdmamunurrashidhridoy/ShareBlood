<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\BloodRequest;
use App\Models\BloodRequestDonor;

class DonorInterestedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected BloodRequest $bloodRequest,
        protected BloodRequestDonor $response
    ) {

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
        return [
            'type' => 'donor_interested',
            'title' => 'A donor is interested in your request',
            'message' => "{$this->response->donor?->name} showed interest in donating for {$this->bloodRequest->patient_name}.",
            'blood_request_id' => $this->bloodRequest->id,
            'blood_request_donor_id' => $this->response->id,
            'donor_user_id' => $this->response->donor_user_id,
            'donor_name' => $this->response->donor?->name,
            'patient_name' => $this->bloodRequest->patient_name,
            'status' => $this->response->status,
            'action_url' => route('blood-requests.show', $this->bloodRequest),
        ];
    }
}
