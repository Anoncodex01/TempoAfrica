<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = [
        'name',
        'country_id',
        'zip_code',
        'is_established',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function districts()
    {
        return $this->hasMany(District::class, 'province_id');
    }
}
