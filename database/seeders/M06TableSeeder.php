<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\M06;
use Carbon\Carbon;

class M06TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample Parent 1
        
        M06::create([
            'd_code' => 'M06-00001',
            'd_name' => 'John Doe',
            'lastname' => 'Doe',
            'firstname' => 'John',
            'mi' => 'M',
            'mobileno' => '09123456789',
            'isparent' => true,
            'isguardian' => false,
            'createdby' => 'seeder',
            'createddate' => Carbon::today(),
            'createdtime' => Carbon::now()->format('H:i:s'),
            'updatedby' => 'seeder',
            'updateddate' => Carbon::today(),
            'updatedtime' => Carbon::now()->format('H:i:s'),
        ]);

        // Sample Guardian 1
        M06::create([
            'd_code' => 'M06-00002',
            'd_name' => 'Jane Smith',
            'lastname' => 'Smith',
            'firstname' => 'Jane',
            'mi' => 'L',
            'mobileno' => '09987654321',
            'isparent' => false,
            'isguardian' => true,
            'createdby' => 'seeder',
            'createddate' => Carbon::today(),
            'createdtime' => Carbon::now()->format('H:i:s'),
            'updatedby' => 'seeder',
            'updateddate' => Carbon::today(),
            'updatedtime' => Carbon::now()->format('H:i:s'),
        ]);

        // Sample Parent 2
        M06::create([
            'd_code' => 'M06-00003',
            'd_name' => 'Maria Santos',
            'lastname' => 'Santos',
            'firstname' => 'Maria',
            'mi' => 'C',
            'mobileno' => '09171234567',
            'isparent' => true,
            'isguardian' => false,
            'createdby' => 'seeder',
            'createddate' => Carbon::today(),
            'createdtime' => Carbon::now()->format('H:i:s'),
            'updatedby' => 'seeder',
            'updateddate' => Carbon::today(),
            'updatedtime' => Carbon::now()->format('H:i:s'),
        ]);
    }
}