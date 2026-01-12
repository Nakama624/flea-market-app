<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class ModifiedUserInfoTest extends TestCase
{
  use RefreshDatabase;

  // 変更項目が初期値として過去設定されていること
  // （プロフィール画像、ユーザー名、郵便番号、住所）
  public function test_modify_user_info(){
    $user = User::factory()
      ->profileCompleted()
      ->verified()
      ->create();

    $response = $this->actingAs($user)->get('/mypage/profile');
    $response->assertStatus(200);

    // プロフィール情報の表示
    $response->assertSee($user->name);
    $response->assertSee($user->postcode);
    $response->assertSee($user->address);
    $response->assertSee($user->building);
    $response->assertSee('storage/profiles/' . $user->profile_img, false);
  }
}
