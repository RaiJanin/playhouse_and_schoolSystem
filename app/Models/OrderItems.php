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
        'disc_code',
        'checked_out',
        'lne_xtra_chrg'
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'ord_code_ph', 'ord_code_ph');
    }

    public function child()
    {
        return $this->belongsTo(M06Child::class, 'd_code_child', 'd_code_c');
    }

}
