<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemInteractionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログインユーザーは商品にいいねできる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->postJson("/item/{$item->id}/like")
            ->assertStatus(200)
            ->assertJson([
                'liked' => true,
                'like_count' => 1,
            ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(1, $item->fresh()->like_count);
    }

    /** @test */
    public function ログインユーザーはいいねを解除できる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['like_count' => 1]);

        Like::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'session_id' => session()->getId(),
        ]);

        $this->actingAs($user)
            ->postJson("/item/{$item->id}/like")
            ->assertStatus(200)
            ->assertJson([
                'liked' => false,
                'like_count' => 0,
            ]);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(0, $item->fresh()->like_count);
    }

    public function test_ログインユーザーがいいねを押すと画像が切り替わる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson("/item/{$item->id}/like");

        $response->assertOk()
                    ->assertJson([
                        'liked' => true,
                    ]);

        $response2 = $this->postJson("/item/{$item->id}/like");

        $response2->assertOk()
                    ->assertJson([
                        'liked' => false,
                    ]);
    }

    public function test_ログインユーザーはコメントを投稿できる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['comment_count' => 0]);

        $this->actingAs($user)
            ->post("/item/{$item->id}/comment", [
                'comment' => 'これはテストコメントです。'
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'これはテストコメントです。'
        ]);

        $this->assertEquals(1, $item->fresh()->comment_count);
    }

    public function test_未ログインユーザーはコメントを投稿できない()
    {
        $item = Item::factory()->create();

        $this->post("/item/{$item->id}/comment", [
            'comment' => 'ゲストコメント'
        ])->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'comment' => 'ゲストコメント'
        ]);
    }

    public function test_コメントが空の場合バリデーションエラーとなる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->post("/item/{$item->id}/comment", [
                'comment' => ''
            ])
            ->assertSessionHasErrors(['comment']);
    }

    public function test_コメントが255文字を超えるとバリデーションエラーになる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->post("/item/{$item->id}/comment", [
                'comment' => str_repeat('あ', 256)
            ])
            ->assertSessionHasErrors(['comment']);
    }
}
