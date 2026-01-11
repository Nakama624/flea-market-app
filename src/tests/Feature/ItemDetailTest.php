<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Category;

class ItemDetailTest extends TestCase
{
  use RefreshDatabase;

  public function test_get_items_detail(){

    // ユーザー
    $user = User::create([
      'name' => 'ユーザー1',
      'email' => 'login@example.com',
      'password' => bcrypt('password123'),
    ]);

    // ユーザー(コメント用)
    $commentUser = User::create([
      'name' => 'ユーザー2',
      'email' => 'other@example.com',
      'password' => bcrypt('password123'),
    ]);

    $condition = Condition::create([
      'condition_name' => '新品',
    ]);

    $item1 = Item::create([
      'item_img' => 'test.jpg',
      'name' => '商品A',
      'brand' => 'ブランドA',
      'price' => 1000,
      'description' => '説明A',
      'condition_id' => $condition->id,
      'sell_user_id' => $user->id,
    ]);

    $item2 = Item::create([
      'name' => 'B商品',
      'price' => 2000,
      'brand' => 'ブランドB',
      'condition_id' => $condition->id,
      'description' => '説明B',
      'item_img' => 'test.jpg',
      'sell_user_id' => $user->id,
    ]);

    // いいね
    Like::create(['item_id' => $item1->id, 'user_id' => $user->id]);
    Like::create(['item_id' => $item1->id, 'user_id' => $commentUser->id]);

    Like::create(['item_id' => $item2->id, 'user_id' => $user->id]);

    // コメント
    Comment::create([
      'item_id' => $item1->id,
      'user_id' => $commentUser->id,
      'comment' => 'コメントコメント',
    ]);

    Comment::create([
      'item_id' => $item2->id,
      'user_id' => $user->id,
      'comment' => '別商品のコメント',
    ]);

    // カテゴリー
    $category1 = Category::create(['category_name' => 'ファッション']);
    $category2 = Category::create(['category_name' => '家電']);
    $category3 = Category::create(['category_name' => 'インテリア']);

    $item1->categories()->attach([$category1->id, $category2->id]);

    $response = $this->get('/item/' . $item1->id);
    $response->assertStatus(200);

    // 商品情報の確認
    $response->assertSee($item1->name);
    $response->assertSee($item1->brand);
    $response->assertSee(number_format($item1->price));
    $response->assertSee($item1->description);
    $response->assertSee($condition->condition_name);
    $response->assertSee('storage/items/' . $item1->item_img, false);

    // コメント情報の確認
    $response->assertSee($commentUser->name);
    $response->assertSee('コメントコメント');

    // カテゴリの確認
    // 表示あり
    $response->assertSee($category1->category_name);
    $response->assertSee($category2->category_name);
    // 表示なし
    $response->assertDontSee($category3->category_name);

    // いいね数
    $response->assertSee('<p class="icon__count">2</p>', false);
    // コメント数
    $response->assertSee('コメント(1)');
  }
}
