<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DurationPrices extends Model
{
    protected $table = 'duration_prices';

    protected $fillable = [
        'duration_hour',
        'label', 
        'price'
    ];
}
