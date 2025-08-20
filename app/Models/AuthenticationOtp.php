<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthenticationOtp extends Model
{
    protected $fillable = [
        'phone',
        'is_used',
        'sent_at',
        'used_at',
        'otp',
    ];
}
