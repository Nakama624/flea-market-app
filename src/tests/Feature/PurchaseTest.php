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
    // ユーザー（購入者＆出品者）
    $loginUser = User::factory()->verified()->create();
    $seller = User::factory()->verified()->create();

    $item = Item::factory()
      ->soldBy($seller)
      ->create([
        'name' => '商品A',
        'price' => 1000,
        'brand' => 'ブランドA',
        'description' => '説明A',
        'item_img' => 'test.jpg',
      ]);

    $notSold = Item::factory()
      ->soldBy($loginUser)
      ->create([
        'name' => '商品B',
        'price' => 1200,
        'brand' => 'ブランドB',
        'description' => '説明B',
        'item_img' => 'test.jpg',
      ]);

    // ログイン状態で商品詳細を開く
    $response = $this->actingAs($loginUser)->get('/purchase/' . $item->id);
    $response->assertStatus(200);

    $payment = Payment::factory()->create();

    // 購入する
    $res = $this->actingAs($loginUser)
      ->post('/purchase/' . $item->id, [
        'delivery_postcode' => '111-2222',
        'delivery_address'  => '東京都八王子市111',
        'delivery_building' => 'テストビル101',
        'payment_id'        => $payment->id,
      ]);

    // エラーが出ていないことを確認
    $res->assertSessionHasNoErrors();
    $res->assertStatus(302);

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
