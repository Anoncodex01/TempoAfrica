<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccommodationPhoto extends Model
{
    protected $fillable = [
        'can_show',
        'description',
        'accommodation_id',
        'photo',
    ];

     public function accommodation()
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id');
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