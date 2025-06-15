<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\ItemSeeder;

class ItemTest extends TestCase
{
    use RefreshDatabase;
    public function test_ホーム画面で自分の商品を除いた商品が表示される()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $myItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の商品',
        ]);

        $otherItem = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'name' => '他人の商品',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertDontSee($myItem->name);

        $response->assertSee($otherItem->name);
    }

    public function test_マイリスト画面はログインしていないとリダイレクトされる()
    {
        $response = $this->get('/?page=mylist');
        $response->assertRedirect('/login');
    }

    public function test_マイリスト画面でいいねした商品が表示される()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id, // 別のユーザーの商品
        ]);

        $user->likedItems()->attach($item->id);

        $response = $this->get('/?page=mylist');

        $response->assertStatus(200);

        $response->assertViewHas('items', function ($items) use ($item) {
            return $items->contains('id', $item->id);
        });
    }

    public function test_商品検索ができる()
    {
        $keyword = '腕時計';
        $response = $this->get("/search?query={$keyword}");

        $response->assertStatus(200);
        $response->assertViewHas('items', function ($items) use ($keyword) {
            return $items->every(fn ($item) => str_contains($item->name, $keyword));
        });
    }

    public function test_検索状態がマイリストでも保持される()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'name' => '検索対象の商品',
            'user_id' => $user->id,
        ]);

        $response = $this->get('/?page=mylist&query=検索対象');

        $response->assertStatus(200);
        $response->assertSee('検索対象の商品');
    }
}
