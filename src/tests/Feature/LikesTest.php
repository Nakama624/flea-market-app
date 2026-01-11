<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Like;

class LikesTest extends TestCase
{
  use RefreshDatabase;

  // いいねアイコンを押下することによって、いいねした商品として登録することができる。
  // 追加済みのアイコンは色が変化する
  public function test_increase_likes_item_and_icon_has_active_color()
  {
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

    $likeItem = Item::create([
      'name' => '商品A',
      'price' => 1000,
      'brand' => 'ブランドA',
      'condition_id' => $condition->id,
      'description' => '説明A',
      'item_img' => 'test.jpg',
      'sell_user_id' => $loginUser->id,
    ]);

    // 事前は0件（増加テストのため）
    $this->assertDatabaseMissing('likes', [
      'item_id' => $likeItem->id,
      'user_id' => $loginUser->id,
    ]);

    // いいね押下
    $res = $this->actingAs($loginUser)
      ->from('/item/' . $likeItem->id)
      ->post('/items/' . $likeItem->id . '/like');

    $res->assertRedirect('/item/' . $likeItem->id);

    // DBに登録
    $this->assertDatabaseHas('likes', [
      'item_id' => $likeItem->id,
      'user_id' => $loginUser->id,
    ]);

    // ログイン状態で商品詳細を開く
    $response = $this->actingAs($loginUser)->get('/item/' . $likeItem->id);
    $response->assertStatus(200);

    // いいねが1つ増えている
    $response->assertSee('<p class="icon__count">1</p>', false);

    // いいね押下するとアイコンの色が変わっている(画像)
    $response->assertSee('likes_pink.png', false);
    $response->assertDontSee('likes_default.png', false);
  }

  // 再度いいねアイコンを押下することによって、いいねを解除することができる。
  public function test_decrease_likes_item(){
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

    $item = Item::create([
      'name' => '商品A',
      'price' => 1000,
      'brand' => 'ブランドA',
      'condition_id' => $condition->id,
      'description' => '説明A',
      'item_img' => 'test.jpg',
      'sell_user_id' => $loginUser->id,
    ]);

    // いいねを直接作る
    Like::create([
      'item_id' => $item->id,
      'user_id' => $loginUser->id,
    ]);

    // DBのいいねを確認
    $this->assertDatabaseHas('likes', [
        'item_id' => $item->id,
        'user_id' => $loginUser->id,
    ]);

    // いいね押下
    $res = $this->actingAs($loginUser)
      ->from('/item/' . $item->id)
      ->post('/items/' . $item->id . '/like');

    $res->assertRedirect('/item/' . $item->id);

    // いいね解除確認
    $this->assertDatabaseMissing('likes', [
      'item_id' => $item->id,
      'user_id' => $loginUser->id,
    ]);

    // ログイン状態で商品詳細を開く
    $response = $this->actingAs($loginUser)->get('/item/' . $item->id);
    $response->assertStatus(200);

    // いいねが減っている
    $response->assertSee('<p class="icon__count">0</p>', false);

    // いいね解除するとアイコンの色が戻っている(画像)
    $response->assertDontSee('likes_pink.png', false);
    $response->assertSee('likes_default.png', false);
  }
}
