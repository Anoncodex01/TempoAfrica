<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseBooking extends Model
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
        'house_id',
        'customer_id',
        // REMOVED: is_checked_in, is_checked_out, is_cancelled, from_date, to_date, checked_in_at, checked_out_at
        // These are NOT needed for house information access
        'paid_at',
        'receipt_url',
        'receipt_filename',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'paid_at' => 'datetime',
    ];

    public function house()
    {
        return $this->belongsTo(House::class, 'house_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function getStatusAttribute()
    {
        if ($this->is_paid) {
            return 'paid';
        }
        
        return 'pending';
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'paid' => 'blue',
            'pending' => 'yellow',
            default => 'gray'
        };
    }

    // Duration is not applicable for house information access
    public function getDurationAttribute()
    {
        return 0;
    }
} 