<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function accommodations()
    {
        return $this->belongsToMany(Accommodation::class, 'accommodation_facility');
    }

    public function accommodationRooms()
    {
        return $this->belongsToMany(AccommodationRoom::class, 'accommodation_room_facility');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
} 