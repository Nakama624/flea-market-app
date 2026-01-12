<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Purchase;

class GetUserInfoTest extends TestCase
{
  use RefreshDatabase;

  // 必要な情報が取得できる
  // （プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）
  public function test_display_user_info(){
    $loginUser = User::factory()
      ->profileCompleted()
      ->verified()
      ->create();

    $seller = User::factory()
      ->profileCompleted()
      ->verified()
      ->create();

    // 購入商品
    $boughtItem = Item::factory()
        ->soldBy($seller)
        ->create(['name' => '購入商品']);

    // 出品商品
    $sellItem = Item::factory()
        ->soldBy($loginUser)
        ->create(['name' => '出品商品']);

    // 購入情報を作成
    $payment = Payment::factory()->create();
    Purchase::factory()->create([
      'user_id' => $loginUser->id,
      'item_id' => $boughtItem->id,
      'payment_id' => $payment->id,
      'delivery_postcode' => '111-2222',
      'delivery_address'  => '東京都八王子市111',
      'delivery_building' => 'テストビル101',
      'status' => 'pending',
    ]);

    // 出品タブ
    $sellRes = $this->actingAs($loginUser)->get('/mypage?page=sell');
    $sellRes->assertStatus(200);
    $sellRes->assertSee($loginUser->name);
    $sellRes->assertSee('storage/profiles/' . $loginUser->profile_img, false);
    $sellRes->assertSee($sellItem->name);
    $sellRes->assertDontSee($boughtItem->name);

    // 購入タブ
    $buyRes = $this->actingAs($loginUser)->get('/mypage?page=buy');
    $buyRes->assertStatus(200);
    $buyRes->assertSee($loginUser->name);
    $buyRes->assertSee('storage/profiles/' . $loginUser->profile_img, false);
    $buyRes->assertSee($boughtItem->name);
    $buyRes->assertDontSee($sellItem->name);
  }
}
