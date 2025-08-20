<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
        'name',
        'province_id',
        'zip_code',
        'is_established',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
