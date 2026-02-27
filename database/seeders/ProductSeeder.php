<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            // Drinks
            [
                'name'        => 'Coffee Cappuccino',
                'image'       => 'bg_2.jpg',
                'price'       => '5.55',
                'description' => 'A rich espresso-based drink topped with steamed milk foam. The perfect balance of bold coffee and creamy texture.',
                'type'        => 'drinks',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Mocha',
                'image'       => 'menu-1.jpg',
                'price'       => '6.00',
                'description' => 'A delightful blend of espresso, steamed milk, and chocolate syrup, topped with whipped cream. A coffee lover\'s treat.',
                'type'        => 'drinks',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Caffe Latte',
                'image'       => 'menu-2.jpg',
                'price'       => '5.00',
                'description' => 'Smooth espresso combined with a generous amount of steamed milk and a light layer of foam. A classic and comforting choice.',
                'type'        => 'drinks',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Americano',
                'image'       => 'menu-3.jpg',
                'price'       => '4.50',
                'description' => 'Espresso shots diluted with hot water, creating a lighter yet bold coffee experience. Simple and satisfying.',
                'type'        => 'drinks',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Espresso',
                'image'       => 'menu-4.jpg',
                'price'       => '3.50',
                'description' => 'A concentrated shot of pure coffee brewed by forcing hot water through finely-ground beans. Intense, aromatic, and full-bodied.',
                'type'        => 'drinks',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            // Desserts
            [
                'name'        => 'Cheesecake',
                'image'       => 'dessert-1.jpg',
                'price'       => '7.00',
                'description' => 'Creamy and rich New York-style cheesecake with a buttery graham cracker crust. Served with a fresh berry compote.',
                'type'        => 'desserts',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Pancake',
                'image'       => 'dessert-2.jpg',
                'price'       => '10.00',
                'description' => 'Fluffy, golden-brown pancakes stacked high and drizzled with maple syrup. A classic comfort food that never disappoints.',
                'type'        => 'desserts',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Chocolate Brownie',
                'image'       => 'dessert-3.jpg',
                'price'       => '6.50',
                'description' => 'Dense, fudgy chocolate brownie with a crispy top and gooey center. Best enjoyed warm with a scoop of vanilla ice cream.',
                'type'        => 'desserts',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Tiramisu',
                'image'       => 'dessert-4.jpg',
                'price'       => '8.00',
                'description' => 'Classic Italian dessert made with espresso-soaked ladyfingers layered with mascarpone cream and dusted with cocoa powder.',
                'type'        => 'desserts',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            // Burgers
            [
                'name'        => 'Classic Beef Burger',
                'image'       => 'burger-1.jpg',
                'price'       => '12.00',
                'description' => 'Juicy beef patty with lettuce, tomato, pickles, and our signature sauce, served in a toasted brioche bun.',
                'type'        => 'burgers',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Cheese Burger',
                'image'       => 'burger-2.jpg',
                'price'       => '13.00',
                'description' => 'Our classic beef burger topped with melted cheddar cheese, caramelized onions, and crispy bacon for an extra indulgent bite.',
                'type'        => 'burgers',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Chicken Burger',
                'image'       => 'burger-3.jpg',
                'price'       => '11.00',
                'description' => 'Crispy fried chicken fillet with coleslaw, pickled jalapeños, and honey mustard sauce in a soft sesame bun.',
                'type'        => 'burgers',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
