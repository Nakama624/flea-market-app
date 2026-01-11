<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Like;

class ItemSearchTest extends TestCase
{
  use RefreshDatabase;

  public function test_can_search_items_by_partial_match_of_name(): void{
    // ログインユーザー
    $loginUser = User::create([
      'name' => 'ログインユーザー',
      'email' => 'login@example.com',
      'password' => bcrypt('password123'),
    ]);

    $condition = Condition::create([
      'condition_name' => '新品',
    ]);

    $noHit1 = Item::create([
      'name' => '商品A',
      'price' => 1000,
      'brand' => 'ブランドA',
      'condition_id' => $condition->id,
      'description' => '説明A',
      'item_img' => 'test.jpg',
      'sell_user_id' => $loginUser->id,
    ]);

    // 検索にヒットする商品
    $hit = Item::create([
      'name' => 'B商品',
      'price' => 2000,
      'brand' => 'ブランドB',
      'condition_id' => $condition->id,
      'description' => '説明B',
      'item_img' => 'test.jpg',
      'sell_user_id' => $loginUser->id,
    ]);

    $noHit2 = Item::create([
      'name' => 'C商品',
      'price' => 2000,
      'brand' => 'ブランドB',
      'condition_id' => $condition->id,
      'description' => '説明B',
      'item_img' => 'test.jpg',
      'sell_user_id' => $loginUser->id,
    ]);

    // いいね
    $Like = Like::create([
      'item_id' => $hit->id,
      'user_id' => $loginUser->id,
    ]);

    // ログイン状態で一覧を開く
    $response = $this->actingAs($loginUser)->get('/');

    // 検索ボタン押下
    $response = $this->actingAs($loginUser)
      ->get('/?tab=mylist&keyword=' . urlencode('B'));

    $response->assertStatus(200);

    // 部分一致する商品だけ表示される
    $response->assertSee($hit->name);

    // 一致しないものは表示されない
    $response->assertDontSee($noHit1->name);
    $response->assertDontSee($noHit2->name);
  }

  public function test_search_keyword_is_kept_on_mylist_when_navigating_from_home(): void{
    $loginUser = User::create([
      'name' => 'ログインユーザー',
      'email' => 'login@example.com',
      'password' => bcrypt('password123'),
    ]);

    $condition = Condition::create([
      'condition_name' => '新品',
    ]);

    $hit = Item::create([
      'name' => 'B商品',
      'price' => 2000,
      'brand' => 'ブランドB',
      'condition_id' => $condition->id,
      'description' => '説明B',
      'item_img' => 'test.jpg',
      'sell_user_id' => $loginUser->id,
    ]);

    // mylistに出すためLike
    Like::create([
      'item_id' => $hit->id,
      'user_id' => $loginUser->id,
    ]);

    // 1) ホームで検索（本来の「検索した状態」を作る）
    $home = $this->actingAs($loginUser)
      ->get('/?keyword=' . urlencode('B'));

    $home->assertStatus(200);

    // 2) マイリストへ遷移（検索キーワードを付けた状態で遷移）
    $mylist = $this->actingAs($loginUser)
      ->get('/?tab=mylist&keyword=' . urlencode('B'));

    $mylist->assertStatus(200);

    // 3) 検索キーワードが保持されている（inputのvalueに残っている）
    $mylist->assertSee('name="keyword"', false);
    $mylist->assertSee('value="B"', false);
  }

}
