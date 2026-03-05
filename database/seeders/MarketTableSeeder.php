<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Market;

class MarketTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $markets = [
            ['mkt_code' => '001', 'mkt_desc' => 'WALK-IN', 'branch' => null, 'transferred' => 'N'],
            ['mkt_code' => '002', 'mkt_desc' => 'FACEBOOK ADS', 'branch' => null, 'transferred' => 'N'],
            ['mkt_code' => '003', 'mkt_desc' => 'OLX ADS', 'branch' => null, 'transferred' => 'N'],
            ['mkt_code' => '004', 'mkt_desc' => 'OTHER ONLINE ADS', 'branch' => null, 'transferred' => 'N'],
            ['mkt_code' => '005', 'mkt_desc' => 'APPOINTEMENT', 'branch' => null, 'transferred' => 'N'],
            ['mkt_code' => '006', 'mkt_desc' => 'FLYERS', 'branch' => null, 'transferred' => 'N'],
            ['mkt_code' => '007', 'mkt_desc' => 'MALL DISPLAY', 'branch' => null, 'transferred' => 'N'],
            ['mkt_code' => '008', 'mkt_desc' => 'REFFERAL', 'branch' => null, 'transferred' => 'N'],
        ];

        foreach ($markets as $market) {
            Market::create($market);
        }
    }
}
