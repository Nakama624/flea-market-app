<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use App\Models\Condition;
use App\Models\Payment;
use App\Models\Purchase;

// いいねした商品だけが表示される
class MylistTest extends TestCase
{
  use RefreshDatabase;

  public function test_only_likes_items_on_mylist(){

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

    $likedItem = Item::create([
      'name' => 'いいね商品',
      'price' => 2000,
      'brand' => 'ブランドB',
      'condition_id' => $condition->id,
      'description' => '説明B',
      'item_img' => 'test.jpg',
      'sell_user_id' => $otherUser->id,
    ]);

    // いいね済みにする
    $likeItem = Like::create([
      'user_id' => $loginUser->id,
      'item_id' => $likedItem->id,
    ]);

    // ログイン状態で一覧を開く
    $response = $this->actingAs($loginUser)->get('/?tab=mylist');

    $response->assertStatus(200);

    // いいねした商品は表示される
    $response->assertSee($likedItem->name);

    // いいねしていない商品は表示されない
    $response->assertDontSee($myItem->name);
  }

  // 未認証の場合は何も表示されない
  public function test_mylist_is_empty_when_not_authenticated()
{
    // ユーザー
    $user = User::create([
        'name' => 'ユーザー',
        'email' => 'user@example.com',
        'password' => bcrypt('password123'),
    ]);

    $condition = Condition::create([
        'condition_name' => '新品',
    ]);

    // 商品
    $item = Item::create([
        'name' => 'いいね商品',
        'price' => 2000,
        'brand' => 'ブランドB',
        'condition_id' => $condition->id,
        'description' => '説明B',
        'item_img' => 'test.jpg',
        'sell_user_id' => $user->id,
    ]);

    // 未認証で mylist を開く
    $response = $this->get('/?tab=mylist');

    $response->assertStatus(200);

    // 商品表示なし
    $response->assertDontSee($item->name);
  }

  // 購入済み商品は「Sold」と表示される
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

    // ログインユーザーの商品（表示されないはず）
    $myItem = Item::create([
      'name' => '商品A',
      'price' => 1000,
      'brand' => 'ブランドA',
      'condition_id' => $condition->id,
      'description' => '説明A',
      'item_img' => 'test.jpg',
      'sell_user_id' => $loginUser->id,
    ]);

    // 他ユーザーの商品（表示されるはず）
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
