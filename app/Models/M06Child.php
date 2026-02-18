<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M06Child extends Model
{
    protected $table = 'm06_child';
    protected $primaryKey = 'd_code_c';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'd_code_c',
        'lastname',
        'firstname',
        'mi',
        'birthday',
        'age',
        'createdby',
        'createddate',
        'createdtime',
        'updatedby',
        'updateddate',
        'updatedtime',
        'd_code',
    ];

    protected $casts = [
        'birthday' => 'date',
        'createddate' => 'date',
        'updateddate' => 'date',
    ];

    // Relationship to parent
    public function parent()
    {
        return $this->belongsTo(M06::class, 'd_code', 'd_code');
    }
}