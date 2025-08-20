<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HousePhoto extends Model
{
    protected $fillable = [
        'can_show',
        'description',
        'house_id',
        'photo',
    ];

    public function house()
    {
        return $this->belongsTo(House::class, 'house_id');
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
