<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_info_id',
        'guardian_id',
        'name',
        'birthday',
        'playtime_duration',
        'price',
        'add_socks',
    ];

    protected $casts = [
        'birthday' => 'date',
        'price' => 'decimal:2',
    ];

    // Each child belongs to one parent (loop - one parent can have many children)
    public function parentInfo()
    {
        return $this->belongsTo(ParentInfo::class, 'parent_info_id');
    }

    // Each child can have one guardian (loop - one guardian can have many children)
    public function guardian()
    {
        return $this->belongsTo(Guardian::class, 'guardian_id');
    }

    // Keep backward compatibility with CustomerRecord
    public function customerRecord()
    {
        return $this->belongsTo(CustomerRecord::class);
    }

    // Get the parent or guardian name
    public function getGuardianNameAttribute()
    {
        if ($this->parentInfo) {
            return $this->parentInfo->full_name;
        }
        if ($this->guardian) {
            return $this->guardian->full_name;
        }
        return null;
    }
}
