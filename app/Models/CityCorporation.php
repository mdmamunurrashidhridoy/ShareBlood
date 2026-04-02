<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

class CityCorporation extends Model
{
    protected $fillable = ['district_id', 'name'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function cityAreas()
    {
        return $this->hasMany(CityArea::class);
    }
}
