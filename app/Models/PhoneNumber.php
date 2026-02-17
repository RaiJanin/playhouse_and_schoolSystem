<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    // Many-to-Many: Phone number can belong to many customers
    public function customers()
    {
        return $this->belongsToMany(CustomerRecord::class, 'customer_phone')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    // Get primary customer for this phone
    public function getPrimaryCustomerAttribute()
    {
        return $this->customers()->wherePivot('is_primary', true)->first();
    }
}