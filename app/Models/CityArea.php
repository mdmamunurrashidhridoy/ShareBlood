<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityArea extends Model
{
    protected $fillable = ['city_corporation_id', 'name'];

    public function cityCorporation() 
    {
        return $this->belongsTo(CityCorporation::class);
    }
}
