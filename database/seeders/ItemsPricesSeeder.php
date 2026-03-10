<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ItemsPrices;
use App\Models\DurationPrices;

class ItemsPricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $durations = [
            [
                'duration_hour' => '1',
                'label' => '1 hr = ₱280',
                'price' => 280.00
            ],
            [
                'duration_hour' => '2',
                'label' => '2 hrs = ₱380',
                'price' => 380.00
            ],
            [
                'duration_hour' => '3',
                'label' => '3 hrs = ₱480',
                'price' => 480.00
            ],
            [
                'duration_hour' => 'unlimited',
                'label' => 'Unlimited = ₱580',
                'price' => 580.00
            ]
        ];

        $items = [
            [
                'item' => 'charge_of_minutes',
                'price' => '50'
            ],
            [
                'item' => 'minutes_per_charge',
                'price' => '3'
            ],
            [
                'item' => 'socks_price',
                'price' => '100'
            ],
        ];

        foreach ($durations as $duration) {
            DurationPrices::create($duration);
        }

        foreach ($items as $item) {
            ItemsPrices::create($item);
        }
    }
}
