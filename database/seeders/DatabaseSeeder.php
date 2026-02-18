<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Parent table FIRST
        $this->call([
            M06TableSeeder::class,      // ← Parent table
            M06ChildTableSeeder::class, // ← Child table (after parent)
        ]);
    }
}