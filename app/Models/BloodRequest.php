<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\BloodCompatibility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BloodRequest extends Model
{
    use HasFactory;

    public const STATUSES = ['pending', 'accepted', 'completed', 'cancelled', 'expired'];

    protected $fillable = [
        'requester_user_id',
        'requester_name',
        'requester_phone',

        'patient_name',
        'blood_group',
        'quantity_bags',
        'needed_date',
        'is_emergency',

        'division_id',
        'district_id',
        'upazilla_id',
        'city_corporation_id',
        'city_area_id',

        'hospital_name',
        'address_line',
        'note',

        'status',
        'expires_at',
    ];


    protected $casts = [
        'needed_date' => 'date',
        'expires_at' => 'datetime',
        'is_emergency' => 'boolean',
        'quantity_bags' => 'integer',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_user_id');
    }
    public function division()
    {
        return $this->belongsTo(Division::class);
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }
    public function upazilla()
    {
        return $this->belongsTo(Upazilla::class);
    }

    public function cityCorporation()
    {
        return $this->belongsTo(CityCorporation::class, 'city_corporation_id');
    }

    public function cityArea()
    {
        return $this->belongsTo(CityArea::class);
    }

    public function scopePublicVisible($q)
    {
        return $q->where('status', 'pending')
            ->where(function ($qq) {
                $qq->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }
    public function compatibleDonorBloodGroups(): array
    {
        return BloodCompatibility::compatibleDonorsFor($this->blood_group);
    }

    public function isCompatibleWithUser(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return BloodCompatibility::canDonateTo($user->blood_group, $this->blood_group);
    }

    public function isExactBloodGroupMatchForUser(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return BloodCompatibility::isExactMatch($user->blood_group, $this->blood_group);
    }

    public function scopeCompatibleForUser($query, ?User $user)
    {
        if (!$user || !$user->blood_group) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereIn('blood_group', BloodCompatibility::compatibleRecipientsFor($user->blood_group));
    }

    public function donorResponses(): HasMany
    {
        return $this->hasMany(BloodRequestDonor::class);
    }

    public function donors(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'blood_request_donors',
            'blood_request_id',
            'donor_user_id'
        )->withPivot([
                    'status',
                    'responded_at',
                    'selected_at',
                    'donated_at',
                    'rejected_at',
                    'cancelled_at',
                    'bags_donated',
                    'note',
                    'confirmed_by_user_id',
                ])->withTimestamps();
    }
    public function selectedResponses(): HasMany
    {
        return $this->hasMany(BloodRequestDonor::class)
            ->where('status', BloodRequestDonor::STATUS_SELECTED);
    }

    public function donatedResponses(): HasMany
    {
        return $this->hasMany(BloodRequestDonor::class)
            ->where('status', BloodRequestDonor::STATUS_DONATED);
    }
    public function getNeededBagsCountAttribute(): int
    {
        return $this->quantity_bags ?: 1;
    }
    public function getDonatedBagsCountAttribute(): int
    {
        return (int) $this->donorResponses()
            ->where('status', BloodRequestDonor::STATUS_DONATED)
            ->sum('bags_donated');
    }
    public function getRemainingBagsCountAttribute(): int
    {
        return max(0, $this->needed_bags_count - $this->donated_bags_count);
    }

    public function isFulfilled(): bool
    {
        return $this->donated_bags_count >= $this->needed_bags_count;
    }
}
