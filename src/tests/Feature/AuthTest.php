<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 名前が未入力だとバリデーションエラーになる()
    {
        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function メールアドレスが未入力だとバリデーションエラーになる()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function パスワードが未入力だとバリデーションエラーになる()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function パスワードが8文字未満だとエラーになる()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function パスワードと確認用パスワードが一致しないとエラー()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function 正しい情報で登録できトップ画面にリダイレクト()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/email/verify');
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    /** @test */
    public function ログイン時メールが未入力でエラー()
    {
        $response = $this->post('/login', [
            'password' => 'password123'
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function ログイン時パスワード未入力でエラー()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com'
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function ログイン情報が間違っていたらエラー()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('correct_password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong_password',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function 正しい情報でログイン成功()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/'); // ログイン後のリダイレクト先を確認
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function ログアウトできる()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }
}
