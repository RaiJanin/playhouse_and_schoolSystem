<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $table = 'order_items';
    protected $fillable = [
        'order_id',
        'd_code_child',
        'durationhours',
        'durationsubtotal',
        'issocksadded',
        'socksprice',
        'subtotal'
    ];

    protected $casts = [
        'issocksadded' => 'boolean'
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }
}
