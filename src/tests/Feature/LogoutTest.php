<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class LogoutTest extends TestCase
{
  use RefreshDatabase;

  public function test_logout_function()
  {
    $user = User::factory()->create();

    // ログイン状態を作る
    $this->actingAs($user);

    // 本当にログインできているか確認
    $this->assertAuthenticated();

    // ログアウト処理を実行する
    $this->post('/logout');

    // 未ログイン状態になったことを確認
    $this->assertGuest();
  }
}
