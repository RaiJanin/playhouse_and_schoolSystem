<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $table = 'ordlne_ph';
    protected $fillable = [
        'ord_code_ph',
        'd_code_child',
        'durationhours',
        'durationsubtotal',
        'socksqty',
        'socksprice',
        'subtotal',
        'disc_code'
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'ord_code_ph', 'ord_code_ph');
    }
}
