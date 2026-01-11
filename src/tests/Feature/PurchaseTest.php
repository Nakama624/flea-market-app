<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Purchase;
use App\Models\Payment;

class PurchaseTest extends TestCase
{
  use RefreshDatabase;

  // 「購入する」ボタンを押下すると購入が完了する
  // 購入した商品は商品一覧画面にて「sold」と表示される
  // 「プロフィール/購入した商品一覧」に追加されている
  public function test_logged_in_user_buy_item_and_display_sold_item(){
    // ログインユーザー
    $loginUser = User::create([
      'name' => 'ログインユーザー',
      'email' => 'login@example.com',
      'password' => bcrypt('password123'),
    ]);
    $loginUser->email_verified_at = now();
    $loginUser->save();

    $condition = Condition::create([
      'condition_name' => '新品',
    ]);

    // 別ユーザー
    $seller = User::create([
        'name' => '出品者',
        'email' => 'seller@example.com',
        'password' => bcrypt('password123'),
    ]);
    $seller->email_verified_at = now();
    $seller->save();

    $item = Item::create([
      'name' => '商品A',
      'price' => 1000,
      'brand' => 'ブランドA',
      'condition_id' => $condition->id,
      'description' => '説明A',
      'item_img' => 'test.jpg',
      'sell_user_id' => $seller->id,
    ]);

    // 購入されない商品
    $notSold = Item::create([
      'name' => '商品B',
      'price' => 1200,
      'brand' => 'ブランドB',
      'condition_id' => $condition->id,
      'description' => '説明B',
      'item_img' => 'test.jpg',
      'sell_user_id' => $loginUser->id,
    ]);

    // ログイン状態で商品詳細を開く
    $response = $this->actingAs($loginUser)->get('/purchase/' . $item->id);
    $response->assertStatus(200);

    $payment = Payment::create([
      'payment_method' => 'カード払い',
    ]);

    // 購入する
    $res = $this->actingAs($loginUser)
      ->from('/purchase/' . $item->id)
      ->post('/purchase/' . $item->id, [
        'delivery_postcode' => '111-2222',
        'delivery_address'  => '東京都八王子市111',
        'delivery_building' => 'テストビル101',
        'payment_id'        => $payment->id,
      ]);

    // Purchaseの確認
    $this->assertDatabaseHas('purchases', [
      'user_id'           => $loginUser->id,
      'item_id'           => $item->id,
      'delivery_postcode' => '111-2222',
      'delivery_address'  => '東京都八王子市111',
      'payment_id'        => $payment->id,
      'status'            => 'pending',
    ]);

    // 購入した商品は一覧で「Sold」と表示される
    $response = $this->get('/');
    $response->assertStatus(200);

    $response->assertSee($item->name); 
    $response->assertSeeInOrder([$item->name, 'Sold']);


    // 購入商品はマイリストの「購入した商品」タブで表示される
    $response = $this->actingAs($loginUser)->get('/mypage?page=buy');

    $response->assertSee($item->name);
    $response->assertDontSee($notSold->name);

  }
}
