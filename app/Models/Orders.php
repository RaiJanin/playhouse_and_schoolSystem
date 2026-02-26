<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\M06;
use App\Models\OrderItems;
use Carbon\Carbon;

class Orders extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'order_no',
        'guardian',
        'd_code',
        'totalprice',
        'dsc_code'
    ];

    public function parent()
    {
        return $this->belongsTo(M06::class, 'd_code', 'd_code');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'order_id', 'order_no');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_no = self::generateMonthlyOrderNo();
        });
    }

    private static function generateMonthlyOrderNo()
    {
        $now = Carbon::now();
        $monthLetter = self::getMonthLetter($now->month);

        $startOfMonth = $now->copy()->startOfMonth();

        $lastOrder = self::where('created_at', '>=', $startOfMonth)
            ->where('order_no', 'like', $monthLetter . '%')
            ->orderByDesc('order_no')
            ->first();

        if (!$lastOrder) {
            $sequence = 1;
        } else {
            $lastNumber = (int) substr($lastOrder->order_no, 1);
            $sequence = $lastNumber + 1;
        }

        return $monthLetter . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    private static function getMonthLetter($month)
    {
        $letters = [
            1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D',
            5 => 'E', 6 => 'F', 7 => 'G', 8 => 'H',
            9 => 'I', 10 => 'J', 11 => 'K', 12 => 'L'
        ];

        return $letters[$month] ?? 'Z';
    }
}
