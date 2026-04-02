<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodRequestDonor extends Model
{
    public const STATUS_INTERESTED = 'interested';
    public const STATUS_SELECTED = 'selected';
    public const STATUS_DONATED = 'donated';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'blood_request_id',
        'donor_user_id',
        'status',
        'responded_at',
        'selected_at',
        'donated_at',
        'rejected_at',
        'cancelled_at',
        'bags_donated',
        'note',
        'confirmed_by_user_id',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'selected_at' => 'datetime',
        'donated_at' => 'datetime',
        'rejected_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function bloodRequest(): BelongsTo
    {
        return $this->belongsTo(BloodRequest::class);
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'donor_user_id');
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by_user_id');
    }

    public function isInterested(): bool
    {
        return $this->status === self::STATUS_INTERESTED;
    }

    public function isSelected(): bool
    {
        return $this->status === self::STATUS_SELECTED;
    }

    public function isDonated(): bool
    {
        return $this->status === self::STATUS_DONATED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

}
