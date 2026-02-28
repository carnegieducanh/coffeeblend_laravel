<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insertOrIgnore([
            // Drinks
            [
                'name'           => 'Coffee Cappuccino',
                'image'          => 'bg_2.jpg',
                'price'          => '5.55',
                'description'    => 'A rich espresso-based drink topped with steamed milk foam. The perfect balance of bold coffee and creamy texture.',
                'description_ja' => '濃厚なエスプレッソにスチームドミルクの泡をたっぷり乗せたドリンク。力強いコーヒーとクリーミーな口当たりの絶妙なバランス。',
                'type'           => 'drinks',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'name'           => 'Mocha',
                'image'          => 'menu-1.jpg',
                'price'          => '6.00',
                'description'    => 'A delightful blend of espresso, steamed milk, and chocolate syrup, topped with whipped cream. A coffee lover\'s treat.',
                'description_ja' => 'エスプレッソ、スチームドミルク、チョコレートシロップを合わせ、ホイップクリームをトッピングした至福の一杯。コーヒー好きにはたまらない贅沢な味わい。',
                'type'           => 'drinks',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'name'           => 'Caffe Latte',
                'image'          => 'menu-2.jpg',
                'price'          => '5.00',
                'description'    => 'Smooth espresso combined with a generous amount of steamed milk and a light layer of foam. A classic and comforting choice.',
                'description_ja' => 'まろやかなエスプレッソにたっぷりのスチームドミルクと薄い泡の層を合わせた一杯。定番の安心感あるクラシックコーヒー。',
                'type'           => 'drinks',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'name'           => 'Americano',
                'image'          => 'menu-3.jpg',
                'price'          => '4.50',
                'description'    => 'Espresso shots diluted with hot water, creating a lighter yet bold coffee experience. Simple and satisfying.',
                'description_ja' => 'エスプレッソをお湯で割り、すっきりとしながらも深みのある風味に仕上げたコーヒー。シンプルで飲み応えのある一杯。',
                'type'           => 'drinks',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'name'           => 'Espresso',
                'image'          => 'menu-4.jpg',
                'price'          => '3.50',
                'description'    => 'A concentrated shot of pure coffee brewed by forcing hot water through finely-ground beans. Intense, aromatic, and full-bodied.',
                'description_ja' => '細かく挽いた豆に高圧でお湯を通して抽出した、純粋なコーヒーの濃縮ショット。強い香りとコク深いフルボディが楽しめます。',
                'type'           => 'drinks',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // Desserts
            [
                'name'           => 'Cheesecake',
                'image'          => 'dessert-1.jpg',
                'price'          => '7.00',
                'description'    => 'Creamy and rich New York-style cheesecake with a buttery graham cracker crust. Served with a fresh berry compote.',
                'description_ja' => 'バター風味のグラハムクラッカークラストに、クリーミーでリッチなニューヨークスタイルのチーズケーキ。フレッシュベリーコンポートを添えてご提供。',
                'type'           => 'desserts',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'name'           => 'Pancake',
                'image'          => 'dessert-2.jpg',
                'price'          => '10.00',
                'description'    => 'Fluffy, golden-brown pancakes stacked high and drizzled with maple syrup. A classic comfort food that never disappoints.',
                'description_ja' => 'ふわふわでこんがり黄金色に焼いたパンケーキを高く積み上げ、メープルシロップをたっぷりかけて。何度食べても飽きない定番のコンフォートフード。',
                'type'           => 'desserts',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'name'           => 'Chocolate Brownie',
                'image'          => 'dessert-3.jpg',
                'price'          => '6.50',
                'description'    => 'Dense, fudgy chocolate brownie with a crispy top and gooey center. Best enjoyed warm with a scoop of vanilla ice cream.',
                'description_ja' => 'さくっとした表面ととろけるような中身が絶妙な、濃厚チョコレートブラウニー。バニラアイスを添えて温かいうちにどうぞ。',
                'type'           => 'desserts',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'name'           => 'Tiramisu',
                'image'          => 'dessert-4.jpg',
                'price'          => '8.00',
                'description'    => 'Classic Italian dessert made with espresso-soaked ladyfingers layered with mascarpone cream and dusted with cocoa powder.',
                'description_ja' => 'エスプレッソをたっぷり含ませたレディフィンガーにマスカルポーネクリームを重ね、ココアパウダーをふりかけたクラシックなイタリアンデザート。',
                'type'           => 'desserts',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // Burgers
            [
                'name'           => 'Classic Beef Burger',
                'image'          => 'burger-1.jpg',
                'price'          => '12.00',
                'description'    => 'Juicy beef patty with lettuce, tomato, pickles, and our signature sauce, served in a toasted brioche bun.',
                'description_ja' => 'レタス、トマト、ピクルス、シグネチャーソースをトーストしたブリオッシュバンに挟んだジューシーなビーフパティ。',
                'type'           => 'burgers',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'name'           => 'Cheese Burger',
                'image'          => 'burger-2.jpg',
                'price'          => '13.00',
                'description'    => 'Our classic beef burger topped with melted cheddar cheese, caramelized onions, and crispy bacon for an extra indulgent bite.',
                'description_ja' => 'とろけるチェダーチーズ、キャラメライズドオニオン、カリカリベーコンをトッピングした、クラシックビーフバーガーの贅沢バージョン。',
                'type'           => 'burgers',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'name'           => 'Chicken Burger',
                'image'          => 'burger-3.jpg',
                'price'          => '11.00',
                'description'    => 'Crispy fried chicken fillet with coleslaw, pickled jalapeños, and honey mustard sauce in a soft sesame bun.',
                'description_ja' => 'サクサクのフライドチキンフィレにコールスロー、ピクルスのハラペーニョ、ハニーマスタードソースを合わせたごまバンサンドイッチ。',
                'type'           => 'burgers',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
