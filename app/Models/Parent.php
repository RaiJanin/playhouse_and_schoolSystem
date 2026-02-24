<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    use HasFactory;

    protected $table = 'parent_info'; // Uses the same table as ParentInfo

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'birthday',
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    // One parent can have many children (loop capability)
    public function children()
    {
        return $this->hasMany(Child::class);
    }

    // One parent can have many phone numbers
    public function phoneNumbers()
    {
        return $this->belongsToMany(PhoneNumber::class, 'parent_phone')
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
