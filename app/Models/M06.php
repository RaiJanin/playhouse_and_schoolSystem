<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
    public function children()
    {
        return $this->hasMany(M06Child::class, 'd_code', 'd_code');
    }

    public function guardians()
    {
        return $this->hasMany(M06::class, 'createdby', 'd_name')
                    ->where('isguardian', true)
                    ->where('isparent', false);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $prefix = "M06-";

            $lastCode = self::where('d_code', 'like', "{$prefix}%")
                ->orderBy('d_code', 'desc')
                ->value('d_code');

            if ($lastCode) {
                $number = (int) substr($lastCode, -5) + 1;
            } else {
                $number = 1;
            }

            $model->d_code = $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
        });
    }

}