<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\Item;
use App\Models\User;
use App\Models\Comment;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = User::factory(10)->create();

        $items = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'condition' => '良好',
                'category' =>'アクセサリー' ,
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'condition' => '目立った傷や汚れなし',
                'category' =>'家電' ,
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセット',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'condition' => 'やや傷や汚れあり',
                'category' =>'キッチン' ,
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'condition' => '状態が悪い',
                'category' =>'アクセサリー' ,
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'condition' => '良好',
                'category' =>'家電' ,
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'condition' => '目立った傷や汚れなし',
                'category' =>'ゲーム' ,
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'condition' => 'やや傷や汚れあり',
                'category' =>'アクセサリー' ,
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'condition' => '状態が悪い',
                'category' =>'キッチン' ,
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'condition' => '良好',
                'category' =>'キッチン' ,
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'condition' => '目立った傷や汚れなし',
                'category' =>'コスメ' ,
            ],
        ];

        foreach ($items as &$item) {

            $imageUrl = $item['image'];
            $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
            $filename = Str::random(10) . '.' . $extension;

            $response = Http::get($imageUrl);

                if ($response->successful()) {
                    Storage::disk('public')->put('item_images/' . $filename, $response->body());
                    $item['image'] = 'item_images/' . $filename;
                } else {
                    $item['image'] = 'item_images/default.png';
                }


            $item['like_count'] = rand(0, 50);
            $item['comment_count'] = rand(0, 10);
            $item['user_id'] = User::inRandomOrder()->first()->id;
            $item['created_at'] = now();
            $item['updated_at'] = now();

        }

        DB::table('items')->insert($items);

        $users = User::all();
        $items = DB::table('items')->get(); // 挿入した商品を取得
        $comments = [
            '素晴らしい商品です！',
            'また購入したいです。',
            'とても満足しています。',
            '迅速な対応ありがとうございました。',
            '商品の状態が良かったです。',
            '価格が手頃で助かりました。',
            '梱包が丁寧でした。',
            '配送が早かったです。',
            '説明通りの商品でした。',
            '期待以上の品質でした。',
        ];

        foreach ($items as $item) {
            for ($i = 0; $i < $item->comment_count; $i++) { // 商品の comment_count に基づいてコメントを生成
                DB::table('comments')->insert([
                    'comment' => $comments[array_rand($comments)],
                    'user_id' => $users->random()->id,
                    'item_id' => $item->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
