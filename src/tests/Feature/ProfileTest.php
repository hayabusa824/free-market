<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function マイページにユーザー情報と出品商品と購入商品が表示される()
    {
        // ストレージのモック（画像確認用）
        Storage::fake('public');

        // Arrange: ユーザーとアドレス・出品商品・購入商品を作成
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'name' => 'テストユーザー',
            'profile_image' => 'profile_images/test.jpg', // 仮画像パス
        ]);

        // 出品商品
        $sellItems = Item::factory()->count(2)->create([
            'user_id' => $user->id,
        ]);

        // 購入商品
        $buyItems = Item::factory()->count(2)->create([
            'buyer_id' => $user->id,
        ]);

        // Act: 出品タブにアクセス
        $responseSell = $this->actingAs($user)->get('/mypage?tab=sell');

        // Assert: 出品情報とユーザー情報が表示されている
        $responseSell->assertStatus(200);
        $responseSell->assertSee($address->name); // ユーザー名
        $responseSell->assertSee('プロフィールを編集');
        foreach ($sellItems as $item) {
            $responseSell->assertSee($item->name);
        }

        // Act: 購入タブにアクセス
        $responseBuy = $this->actingAs($user)->get('/mypage?tab=buy');

        // Assert: 購入情報が表示されている
        $responseBuy->assertStatus(200);
        foreach ($buyItems as $item) {
            $responseBuy->assertSee($item->name);
        }
    }
}