<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class M06Guardian extends Model
{
    protected $table = 'm06_guardian';
    protected $primaryKey = 'd_code_g';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'd_code_g',
        'd_code',
        'd_code_c',
        'd_name',
        'lastname',
        'firstname',
        'mi',
        'birthday',
        'mobileno',
        'email',
        'isparent',
        'isguardian',
        'guardianauthorized',
        'createdby',
        'updatedby',
    ];

    protected $casts = [
        'birthday' => 'date',
        'isparent' => 'boolean',
        'isguardian' => 'boolean',
        'guardianauthorized' => 'boolean'
    ];

    // Relationship to children
    public function m06child()
    {
        return $this->hasMany(M06Child::class, 'd_code_c', 'd_code_c');
    }

    public function parent()
    {
        return $this->belongsTo(M06::class, 'd_code', 'd_code');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $prefix = "M06G-";

            $lastCode = self::where('d_code_g', 'like', "{$prefix}%")
                ->orderBy('d_code_g', 'desc')
                ->value('d_code_g');

            if ($lastCode) {
                $number = (int) substr($lastCode, -5) + 1;
            } else {
                $number = 1;
            }

            $model->d_code_g = $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
        });
    }

}