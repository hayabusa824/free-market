<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\ItemSeeder;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp(); // これがないと RefreshDatabase が効かないことがある
    }

    public function test_商品詳細ページが表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get(route('purchase.show', $item->id));

        $response->assertStatus(200)
                ->assertViewIs('purchase')
                ->assertViewHasAll(['item', 'user', 'address']);
    }

    public function test_商品を購入できる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);
        $address = Address::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('purchase.store', $item->id), [
            'payment_method' => 'credit_card',
        ]);

        $response->assertRedirect('/');

        // 購入テーブルにデータが保存されているか確認
        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'tel' => $address->tel,
            'address' => $address->address,
            'building' => $address->building,
            'payment_method' => 'credit_card',
        ]);

        $item->refresh();
        $this->assertEquals(1, $item->is_sold); // 1 を確認する
    }

    public function test_支払い方法を選択すると小計画面に即時反映される()
    {
        $this->markTestIncomplete('JavaScriptによる動作のため、このテストは不要です。');
    }

    public function test_送付先住所変更画面で登録した住所が商品購入画面に反映される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->withSession([
                "purchase_address_{$item->id}" => [
                    'tel' => '09012345678',
                    'address' => '東京都新宿区1-1-1',
                    'building' => '新宿ビル101',
                ]
            ]);

        $response = $this->get(route('purchase.show', $item->id));

        $response->assertStatus(200)
            ->assertViewHas('address', function ($address) {
                return $address &&
                    $address->tel === '09012345678' &&
                    $address->address === '東京都新宿区1-1-1' &&
                    $address->building === '新宿ビル101';
            });
    }


    public function test_購入した商品に送付先住所が紐づいて登録される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        Address::factory()->create([
            'user_id' => $user->id,
            'tel' => '000-0000',
            'address' => 'ダミー住所',
            'building' => 'ダミービル',
        ]);

        $response = $this->actingAs($user)
            ->withSession([
                "purchase_address_{$item->id}" => [
                    'tel' => '012-3456',
                    'address' => '東京都新宿区1-1-1',
                    'building' => '新宿ビル101',
                ]
            ])
            ->post(route('purchase.store', $item->id), [
                'payment_method' => 'コンビニ払い',
            ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'tel' => '012-3456',
            'address' => '東京都新宿区1-1-1',
            'building' => '新宿ビル101',
            'payment_method' => 'コンビニ払い',
        ]);
    }

    public function test_購入後に商品一覧でsoldが表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['is_sold' => false]);
        $address = Address::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $this->post(route('purchase.store', $item->id), [
            'payment_method' => 'コンビニ払い',
        ])->assertRedirect('/');

        // 売り切れになっていることを確認
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'is_sold' => true,
        ]);

        // ビューにSOLD OUT表示されていることを確認
        $response = $this->get('/');
        $response->assertSee('SOLD OUT');
    }
}
