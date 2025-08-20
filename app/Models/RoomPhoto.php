<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomPhoto extends Model
{
      protected $fillable = [
        'can_show',
        'description',
        'room_id',
        'photo',
    ];

     public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}