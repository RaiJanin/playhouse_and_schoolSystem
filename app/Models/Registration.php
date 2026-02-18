<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_record_id',
        'registration_type',
        'status',
        'qr_code',
        'order_number',
    ];

    // Each registration belongs to one customer
    public function customerRecord()
    {
        return $this->belongsTo(CustomerRecord::class);
    }
}
