<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Purchase;
use App\Models\Payment;

class ChangeDeliveryAddressTest extends TestCase
{
  use RefreshDatabase;
  
  // 送付先住所変更画面にて登録した住所が商品購入画面に反映されている
  // 購入した商品に送付先住所が紐づいて登録される
  public function test_change_delivery_address(){
    // ログインユーザー（購入者）
    $loginUser = User::create([
      'name' => 'ログインユーザー',
      'email' => 'login@example.com',
      'password' => bcrypt('password123'),
      'email_verified_at' => now(),
    ]);

    $loginUser->forceFill(['email_verified_at' => now()])->save();

    $condition = Condition::create([
      'condition_name' => '新品',
    ]);

    $item = Item::create([
      'name' => '商品A',
      'price' => 1000,
      'brand' => 'ブランドA',
      'condition_id' => $condition->id,
      'description' => '説明A',
      'item_img' => 'test.jpg',
      'sell_user_id' => $loginUser->id,
    ]);

    $payment = Payment::create([
      'payment_method' => 'カード払い',
    ]);

    // 購入画面に住所が反映される
    $purchasePage = $this->actingAs($loginUser)
      ->followingRedirects()
      ->post('/purchase/address/' . $item->id, [
        'delivery_postcode' => '111-2222',
        'delivery_address'  => '東京都八王子市111',
        'delivery_building' => 'テストビル101',
      ]);

    $purchasePage->assertStatus(200);
    $purchasePage->assertSee('〒111-2222');
    $purchasePage->assertSee('東京都八王子市111');
    $purchasePage->assertSee('テストビル101');

    // Purchase に住所が保存される
    $buy = $this->actingAs($loginUser)
      ->post('/purchase/' . $item->id, [
          'delivery_postcode' => '111-2222',
          'delivery_address'  => '東京都八王子市111',
          'delivery_building' => 'テストビル101',
          'payment_id'        => $payment->id,
        ]);

    // Stripe にリダイレクトされる想定
    $buy->assertStatus(302);

    // Purchase に正しく保存されている
    $this->assertDatabaseHas('purchases', [
      'user_id'           => $loginUser->id,
      'item_id'           => $item->id,
      'delivery_postcode' => '111-2222',
      'delivery_address'  => '東京都八王子市111',
      'delivery_building' => 'テストビル101',
      'payment_id'        => $payment->id,
      'status'            => 'pending',
    ]);
  }
}
