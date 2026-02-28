<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $descriptions = [
            'Coffee Cappuccino'  => '濃厚なエスプレッソにスチームドミルクの泡をたっぷり乗せたドリンク。力強いコーヒーとクリーミーな口当たりの絶妙なバランス。',
            'Coffee Capuccino'   => '濃厚なエスプレッソにスチームドミルクの泡をたっぷり乗せたドリンク。力強いコーヒーとクリーミーな口当たりの絶妙なバランス。',
            'Mocha'              => 'エスプレッソ、スチームドミルク、チョコレートシロップを合わせ、ホイップクリームをトッピングした至福の一杯。コーヒー好きにはたまらない贅沢な味わい。',
            'Caffe Latte'        => 'まろやかなエスプレッソにたっぷりのスチームドミルクと薄い泡の層を合わせた一杯。定番の安心感あるクラシックコーヒー。',
            'Americano'          => 'エスプレッソをお湯で割り、すっきりとしながらも深みのある風味に仕上げたコーヒー。シンプルで飲み応えのある一杯。',
            'Espresso'           => '細かく挽いた豆に高圧でお湯を通して抽出した、純粋なコーヒーの濃縮ショット。強い香りとコク深いフルボディが楽しめます。',
            'Cheesecake'         => 'バター風味のグラハムクラッカークラストに、クリーミーでリッチなニューヨークスタイルのチーズケーキ。フレッシュベリーコンポートを添えてご提供。',
            'Pancake'            => 'ふわふわでこんがり黄金色に焼いたパンケーキを高く積み上げ、メープルシロップをたっぷりかけて。何度食べても飽きない定番のコンフォートフード。',
            'Chocolate Brownie'  => 'さくっとした表面ととろけるような中身が絶妙な、濃厚チョコレートブラウニー。バニラアイスを添えて温かいうちにどうぞ。',
            'Tiramisu'           => 'エスプレッソをたっぷり含ませたレディフィンガーにマスカルポーネクリームを重ね、ココアパウダーをふりかけたクラシックなイタリアンデザート。',
            'Classic Beef Burger'=> 'レタス、トマト、ピクルス、シグネチャーソースをトーストしたブリオッシュバンに挟んだジューシーなビーフパティ。',
            'Cheese Burger'      => 'とろけるチェダーチーズ、キャラメライズドオニオン、カリカリベーコンをトッピングした、クラシックビーフバーガーの贅沢バージョン。',
            'Chicken Burger'     => 'サクサクのフライドチキンフィレにコールスロー、ピクルスのハラペーニョ、ハニーマスタードソースを合わせたごまバンサンドイッチ。',
        ];

        foreach ($descriptions as $name => $ja) {
            DB::table('products')
                ->where('name', $name)
                ->whereNull('description_ja')
                ->update(['description_ja' => $ja]);
        }
    }

    public function down(): void
    {
        DB::table('products')->update(['description_ja' => null]);
    }
};
