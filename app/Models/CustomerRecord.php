<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'birthday',
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    // Many-to-Many: Customer can have many phone numbers
    public function phoneNumbers()
    {
        return $this->belongsToMany(PhoneNumber::class, 'customer_phone')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    public function children()
    {
        return $this->hasMany(Child::class);
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Get primary phone number
    public function getPrimaryPhoneAttribute()
    {
        return $this->phoneNumbers()->wherePivot('is_primary', true)->first();
    }
}