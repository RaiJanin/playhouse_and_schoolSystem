<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'admin',
                'email' => 'rightappsofficial@gmail.com',
                'password' => Hash::make('adminrss')
            ],
            [
                'name' => 'PLADMIN',
                'email' => 'rightapps.mimo@gmail.com',
                'password' => Hash::make('pladminrai')
            ]
        ];

        foreach($users as $user)
        {
            User::create($user);
        }
    }
}
