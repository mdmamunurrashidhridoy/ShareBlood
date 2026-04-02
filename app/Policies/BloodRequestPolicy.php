<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BloodRequest;

class BloodRequestPolicy
{
    
    public function __construct()
    {
        //
    }

    public function update(User $user, BloodRequest $bloodRequest) 
    {
        return $bloodRequest->requester_user_id === $user->id;
    }

    public function cancel(User $user, BloodRequest $bloodRequest)
    {
        return $bloodRequest->requester_user_id === $user->id 
            && $bloodRequest->status === 'pending';
    }

    public function complete(User $user, BloodRequest $bloodRequest)
    {
        return $bloodRequest->requester_user_id === $user->id 
            && $bloodRequest->status === 'pending';
    }
}
