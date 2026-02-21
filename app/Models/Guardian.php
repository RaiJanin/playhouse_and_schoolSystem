<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'birthday',
        'relationship', // e.g., 'mother', 'father', 'aunt', 'uncle', 'other'
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    // One guardian can have many children (loop capability)
    public function children()
    {
        return $this->hasMany(Child::class);
    }

    // One guardian can have many parents (many-to-many relationship)
    public function parentInfos()
    {
        return $this->belongsToMany(ParentInfo::class, 'parent_guardian')
                    ->withTimestamps();
    }

    // One guardian can have many phone numbers
    public function phoneNumbers()
    {
        return $this->belongsToMany(PhoneNumber::class, 'guardian_phone')
                    ->withPivot('is_primary')
                    ->withTimestamps();
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
