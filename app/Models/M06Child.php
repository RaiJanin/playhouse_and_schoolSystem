<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'phoneno',
        'photo',
        'createdby',
        'updatedby',
        'd_code',
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    // Relationship to parent
    public function parent()
    {
        return $this->belongsTo(M06::class, 'd_code', 'd_code');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            DB::transaction(function () use ($model) {

                $prefix = 'M06C-';

                $lastCode = self::where('d_code_c', 'like', $prefix . '%')
                    ->orderBy('d_code_c', 'desc')
                    ->lockForUpdate()
                    ->value('d_code_c');

                if ($lastCode) {
                    $number = (int) substr($lastCode, -5) + 1;
                } else {
                    $number = 1;
                }

                $model->d_code_c = $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
            });
        });
    }
}