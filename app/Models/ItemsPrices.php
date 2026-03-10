<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemsPrices extends Model
{
    protected $table = 'item_prices';
    public $timestamps = false;

    protected $fillable = [
        'item',
        'price'
    ];
}
