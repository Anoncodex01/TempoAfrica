<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccommodationRoom extends Model
{
    protected $fillable = [
        'name',
        'unique_number',
        'accommodation_id',
        'is_active',
        'currency',
        'price_duration',
        'description',
        'price',
        'is_visible',
        'is_available',
        'category',
        'photo',
    ];

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id');
    }

    public function photos()
    {
        return $this->hasMany(AccommodationRoomPhoto::class, 'accommodation_room_id');
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'accommodation_room_facility');
    }

    protected $appends = ['photo_url'];

    public function getPhotoUrlAttribute()
    {
        if (!$this->photo) {
            return null;
        }
        
        // Remove storage/ prefix if present
        $path = $this->photo;
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }
        
        return $path;
    }
}
