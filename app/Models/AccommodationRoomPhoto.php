<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccommodationRoomPhoto extends Model
{
    protected $fillable = [
        'can_show',
        'description',
        'accommodation_room_id',
        'photo',
    ];

    public function accommodation_room()
    {
        return $this->belongsTo(AccommodationRoom::class, 'accommodation_room_id');
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
