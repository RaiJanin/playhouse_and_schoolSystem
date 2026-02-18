<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\M06Child;
use Carbon\Carbon;

class M06ChildTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Child of M06-00001 (John Doe)
        M06Child::create([
            'd_code_c' => 'M06C-00001',
            'lastname' => 'Doe',
            'firstname' => 'Emma',
            'birthday' => '2020-05-15',
            'age' => 5,
            'd_code' => 'M06-00001',
            'createdby' => 'seeder',
            'createddate' => Carbon::today(),
            'createdtime' => Carbon::now()->format('H:i:s'),
            'updatedby' => 'seeder',
            'updateddate' => Carbon::today(),
            'updatedtime' => Carbon::now()->format('H:i:s'),
        ]);

        // Second child of M06-00001
        M06Child::create([
            'd_code_c' => 'M06C-00002',
            'lastname' => 'Doe',
            'firstname' => 'Liam',
            'birthday' => '2022-11-20',
            'age' => 3,
            'd_code' => 'M06-00001',
            'createdby' => 'seeder',
            'createddate' => Carbon::today(),
            'createdtime' => Carbon::now()->format('H:i:s'),
            'updatedby' => 'seeder',
            'updateddate' => Carbon::today(),
            'updatedtime' => Carbon::now()->format('H:i:s'),
        ]);

        // Child of M06-00003 (Maria Santos)
        M06Child::create([
            'd_code_c' => 'M06C-00003',
            'lastname' => 'Santos',
            'firstname' => 'Miguel',
            'birthday' => '2019-08-10',
            'age' => 6,
            'd_code' => 'M06-00003',
            'createdby' => 'seeder',
            'createddate' => Carbon::today(),
            'createdtime' => Carbon::now()->format('H:i:s'),
            'updatedby' => 'seeder',
            'updateddate' => Carbon::today(),
            'updatedtime' => Carbon::now()->format('H:i:s'),
        ]);
    }
}