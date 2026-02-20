<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\M06;

class PhoneNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'otp_code',
        'otp_expires_at',
        'otp_verified_at',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];
}