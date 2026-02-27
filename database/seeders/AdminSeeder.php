<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->insertOrIgnore([
            [
                'name'       => 'Mr Coffeeblend',
                'email'      => 'coffeeblend@exam.com',
                'password'   => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Mr Admin',
                'email'      => 'admin@exam.com',
                'password'   => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
