<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'is_available',
        'last_donate_date',
        'next_eligible_date',
        'note',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'last_donate_date' => 'date',
        'next_eligible_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
}
