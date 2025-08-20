<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'is_paid',
        'reference',
        'payment_token',
        'payment_url',
        'amount_paid',
        'currency',
        'price',
        'amount',
        'accommodation_id',
        'accommodation_room_id',
        'customer_id',
        'is_checked_in',
        'is_checked_out',
        'is_cancelled',
        'pacs',
        'from_date',
        'to_date',
        'checked_in_at',
        'checked_out_at',
        'paid_at',
        'receipt_url',
        'receipt_filename',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'is_checked_in' => 'boolean',
        'is_checked_out' => 'boolean',
        'is_cancelled' => 'boolean',
        'from_date' => 'date',
        'to_date' => 'date',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id');
    }

    public function accommodationRoom()
    {
        return $this->belongsTo(AccommodationRoom::class, 'accommodation_room_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function getStatusAttribute()
    {
        if ($this->is_cancelled) {
            return 'cancelled';
        }
        
        if ($this->is_checked_out) {
            return 'checked_out';
        }
        
        if ($this->is_checked_in) {
            return 'checked_in';
        }
        
        if ($this->is_paid) {
            return 'paid';
        }
        
        return 'pending';
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'cancelled' => 'red',
            'checked_out' => 'gray',
            'checked_in' => 'green',
            'paid' => 'blue',
            'pending' => 'yellow',
            default => 'gray'
        };
    }

    public function getDurationAttribute()
    {
        if ($this->from_date && $this->to_date) {
            return $this->from_date->diffInDays($this->to_date);
        }
        return 0;
    }
}
