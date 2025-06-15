<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class SellTest extends TestCase
{
    use RefreshDatabase;
    public function test_商品出品画面から必要な情報が保存できる()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'email_verified_at' => now(), // 認証済み
        ]);

        $this->actingAs($user);


        $postData = [
            'category' => ['ファッション', 'メンズ'],
            'condition' => '良好',
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'これはテスト用の商品です。',
            'price' => '1234',
            'image' => UploadedFile::fake()->create('sample.jpg', 500, 'image/jpeg'),
        ];

        $response = $this->post('/', $postData);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'condition' => '良好',
            'description' => 'これはテスト用の商品です。',
            'price' => 1234,
            'user_id' => $user->id,
            // categoryは "ファッション,メンズ" に変換されて保存されているはず
            'category' => 'ファッション,メンズ',
        ]);

        // 画像が保存されていることも確認
        $item = Item::latest()->first();
        Storage::disk('public')->assertExists($item->image);
    }
}
