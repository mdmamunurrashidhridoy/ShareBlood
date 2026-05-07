<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use DivisionByZeroError;
use App\Support\BloodCompatibility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'blood_group',
        'division_id',
        'district_id',
        'upazilla_id',
        'city_corporation_id',
        'city_area_id',
        'address_line',
        'role',
        'medical_history',
        'is_blocked',
        'is_verified'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_blocked' => 'boolean',
            'is_verified' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDonorAvailable()
    {
        return (bool) optional($this->donorProfile)->is_available;
    }


    public function donorProfile()
    {
        return $this->hasOne(DonorProfile::class);
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
        return $this->belongsTo(CityCorporation::class);
    }

    public function cityArea()
    {
        return $this->belongsTo(CityArea::class);
    }
    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class, 'requester_user_id');
    }

    public function bloodRequestsMade(): HasMany
    {
        return $this->hasMany(BloodRequest::class, 'requester_user_id');
    }

    public function bloodRequestResponses(): HasMany
    {
        return $this->hasMany(BloodRequestDonor::class, 'donor_user_id');
    }
    public function donatedRequests(): BelongsToMany
    {
        return $this->belongsToMany(
            BloodRequest::class,
            'blood_request_donors',
            'donor_user_id',
            'blood_request_id'
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

    public function successfulDonations(): HasMany
    {
        return $this->hasMany(BloodRequestDonor::class, 'donor_user_id')
            ->where('status', BloodRequestDonor::STATUS_DONATED);
    }
    public function canDonateTo(?string $recipientBloodGroup): bool
    {
        return BloodCompatibility::canDonateTo($this->blood_group, $recipientBloodGroup);
    }

    public function compatibleRecipientBloodGroups(): array
    {
        return BloodCompatibility::compatibleRecipientsFor($this->blood_group);
    }
}
