<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Address;

class ProfileEditTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function プロフィール編集画面に保存済みのユーザー情報が初期値として表示される()
    {
        // ストレージのモック
        Storage::fake('public');

        // ユーザーとアドレスを作成
        $user = User::factory()->create([
            'email_verified_at' => now(), // メール認証済みにする
        ]);

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'name' => 'テストユーザー',
            'address' => '東京都新宿区1-1-1',
            'tel' => '09012345678',
            'building' => '新宿ビル101',
            'profile_image' => 'profile_images/sample.png',
        ]);

        // ユーザーとしてログイン
        $response = $this->actingAs($user)->get('/mypage/profile');

        // ステータス確認
        $response->assertStatus(200);

        // 各初期値が含まれているかを確認
        $response->assertSee('テストユーザー');
        $response->assertSee('東京都新宿区1-1-1');
        $response->assertSee('09012345678');
        $response->assertSee('新宿ビル101');
        $response->assertSee('profile_images/sample.png');
    }
}
