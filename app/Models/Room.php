<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name',
        'unique_number',
        'accommodation_id',
        'is_active',
        'currency',
        'price_duration',
        'price',
        'is_visible',
        'category',
        'photo',
    ];
}
