<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_record_id',
        'name',
        'birthday',
        'playtime_duration',
        'price',
    ];

    protected $casts = [
        'birthday' => 'date',
        'price' => 'decimal:2',
    ];

    // Each child belongs to one customer
    public function customerRecord()
    {
        return $this->belongsTo(CustomerRecord::class);
    }
}