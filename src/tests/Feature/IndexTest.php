<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Condition;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTest extends TestCase
{
  use RefreshDatabase;

  // 一覧ページを開くと、自分以外の出品商品が表示される
  public function test_index_displays_all_items()
  {
    // ログインユーザー
    $loginUser = User::create([
      'name' => 'ログインユーザー',
      'email' => 'login@example.com',
      'password' => bcrypt('password123'),
    ]);

    // 他ユーザー
    $otherUser = User::create([
      'name' => '他ユーザー',
      'email' => 'other@example.com',
      'password' => bcrypt('password123'),
    ]);

    $condition = Condition::create([
      'condition_name' => '新品',
    ]);

    // ログインユーザーの商品（表示されない）
    $myItem = Item::create([
      'name' => '商品A',
      'price' => 1000,
      'brand' => 'ブランドA',
      'condition_id' => $condition->id,
      'description' => '説明A',
      'item_img' => 'test.jpg',
      'sell_user_id' => $loginUser->id,
    ]);

    // 他ユーザーの商品（表示される）
    $otherItem = Item::create([
      'name' => 'B商品',
      'price' => 2000,
      'brand' => 'ブランドB',
      'condition_id' => $condition->id,
      'description' => '説明B',
      'item_img' => 'test.jpg',
      'sell_user_id' => $otherUser->id,
    ]);

    // ログイン状態で一覧を開く
    $response = $this->actingAs($loginUser)->get('/');

    $response->assertStatus(200);

    // 自分の商品は表示されない
    $response->assertDontSee($myItem->name);

    // 他人の商品は表示される
    $response->assertSee($otherItem->name);
  }


  // 購入済み商品に「Sold」ラベルが表示される
  public function test_sold_item_displays_sold_label()
  {
      // ログインユーザー
    $loginUser = User::create([
      'name' => 'ログインユーザー',
      'email' => 'login@example.com',
      'password' => bcrypt('password123'),
    ]);

    // 他ユーザー
    $otherUser = User::create([
      'name' => '他ユーザー',
      'email' => 'other@example.com',
      'password' => bcrypt('password123'),
    ]);

    $condition = Condition::create([
      'condition_name' => '新品',
    ]);

    $payment = Payment::create([
      'payment_method' => 'コンビニ払い',
    ]);

    // ログインユーザーの商品（表示されない）
    $myItem = Item::create([
      'name' => '商品A',
      'price' => 1000,
      'brand' => 'ブランドA',
      'condition_id' => $condition->id,
      'description' => '説明A',
      'item_img' => 'test.jpg',
      'sell_user_id' => $loginUser->id,
    ]);

    // 他ユーザーの商品（表示される）
    $otherItem = Item::create([
      'name' => 'B商品',
      'price' => 2000,
      'brand' => 'ブランドB',
      'condition_id' => $condition->id,
      'description' => '説明B',
      'item_img' => 'test.jpg',
      'sell_user_id' => $otherUser->id,
    ]);


    // 購入済みにする
    $soldItem = Purchase::create([
      'user_id' => $loginUser->id,
      'item_id' => $otherItem->id,
      'delivery_postcode' => '111-2222',
      'delivery_address' => '東京都八王子市',
      'payment_id' => '1',
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);

    // 「Sold」表示を確認
    $response->assertSee('Sold');
  }
}
