<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    protected $fillable = [
        'name',
        'is_active',
        'registration_number',
        'unique_number',
        'currency',
        'minimum_price_duration',
        'minimum_price',
        'is_visible',
        'is_featured',
        'is_approved',
        'category',
        'customer_id',
        'country_id',
        'province_id',
        'street_id',
        'latitude',
        'longitude',
        'photo',
    ];

    public function rooms()
    {
        return $this->hasMany(AccommodationRoom::class, 'accommodation_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'accommodation_id');
    }

    public function photos()
    {
        return $this->hasMany(AccommodationPhoto::class, 'accommodation_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function street()
    {
        return $this->belongsTo(Street::class, 'street_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'accommodation_facility');
    }

    protected $appends = ['photo_url', 'room_count'];

    public function getRoomCountAttribute()
    {
        return $this->rooms()->count();
    }

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

    public function getPhotosWithUrlsAttribute()
    {
        return $this->photos->map(function ($photo) {
            $photo->photo_url = $photo->photo_url;
            return $photo;
        });
    }
}
