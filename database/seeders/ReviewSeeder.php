<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('reviews')->insertOrIgnore([
            [
                'name'       => 'Sarah Johnson',
                'review'     => 'The cappuccino here is absolutely divine! The atmosphere is cozy and the staff are always so friendly. My go-to spot every morning.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Michael Chen',
                'review'     => 'Best coffee in town, hands down. The mocha is perfectly balanced — not too sweet, not too bitter. The pancakes are a must-try too!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Emily Davis',
                'review'     => 'I love how fresh everything tastes. The tiramisu paired with an espresso is the perfect combo. Will definitely be coming back!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'James Wilson',
                'review'     => 'Incredible ambiance and even better coffee. The latte art they make is stunning. A hidden gem that everyone should know about.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
