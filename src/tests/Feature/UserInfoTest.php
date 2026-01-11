<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class UserInfoTest extends TestCase
{
  use RefreshDatabase;
//  変更項目が初期値として過去設定されていること
// （プロフィール画像、ユーザー名、郵便番号、住所）
  public function test_modify_user_info(){
    // ユーザー
    $user = User::create([
      'name' => 'ユーザー1',
      'email' => 'login@example.com',
      'password' => bcrypt('password123'),
      'postcode' => '111-2222',
      'address'  => '東京都八王子市111',
      'building' => 'テストビル101',
      'profile_img' => 'test.jpg',
    ]);

    $user->forceFill(['email_verified_at' => now()])->save();

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
