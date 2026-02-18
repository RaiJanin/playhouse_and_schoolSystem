<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M06 extends Model
{
    protected $table = 'm06';
    protected $primaryKey = 'd_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'd_code',
        'd_name',
        'lastname',
        'firstname',
        'mi',
        'mobileno',
        'isparent',
        'isguardian',
        'createdby',
        'createddate',
        'createdtime',
        'updatedby',
        'updateddate',
        'updatedtime',
    ];

    protected $casts = [
        'isparent' => 'boolean',
        'isguardian' => 'boolean',
        'createddate' => 'date',
        'updateddate' => 'date',
    ];

    // Relationship to children
    public function children()
    {
        return $this->hasMany(M06Child::class, 'd_code', 'd_code');
    }
}