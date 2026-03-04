<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    protected $table = 'market';
    protected $primaryKey = 'mkt_code';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'mkt_code',
        'mkt_desc',
        'branch',
        'transferred',
    ];

    protected $casts = [
        'mkt_code' => 'string',
        'mkt_desc' => 'string',
        'branch' => 'string',
        'transferred' => 'string'
        ];

    public static function getAllMarket()
    {
        return self::all();
    }

}

