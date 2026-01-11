<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;

class SellItemTest extends TestCase
{
  use RefreshDatabase;

  public function test_sell_item(){

    Storage::fake('public');

    // ユーザー
    $user = User::create([
      'name' => 'ユーザー1',
      'email' => 'login@example.com',
      'password' => bcrypt('password123'),
    ]);

    $user->forceFill(['email_verified_at' => now()])->save();

    $condition = Condition::create([
      'condition_name' => '新品',
    ]);

    // カテゴリー
    $category1 = Category::create(['category_name' => 'ファッション']);
    $category2 = Category::create(['category_name' => '家電']);
    $category3 = Category::create(['category_name' => 'インテリア']);

    // 出品商品が保存される
    $sellItem = $this->actingAs($user)
      ->from('/sell')
      ->post('/sell/', [
        'name' => '商品A',
        'price' => 1000,
        'brand' => 'ブランドA',
        'condition_id' => $condition->id,
        'description' => '説明A',
        'item_img' => UploadedFile::fake()->create('test.png', 100, 'image/png'),
        'category_ids' => [$category1->id, $category2->id],
      ]);

    $sellItem->assertStatus(302);
    $sellItem->assertSessionHasNoErrors();

    // DBに商品が作成されている
    $sellItem = Item::first();
    $this->assertNotNull($sellItem);
    
    // DB：商品情報
    $this->assertDatabaseHas('items', [
      'id' => $sellItem->id,
      'name' => '商品A',
      'price' => 1000,
      'brand' => 'ブランドA',
      'condition_id' => $condition->id,
      'description' => '説明A',
      'sell_user_id' => $user->id,
    ]);

    // DB：中間テーブル（categories_items）に紐付いている
    $this->assertDatabaseHas('categories_items', [
      'item_id' => $sellItem->id,
      'category_id' => $category1->id,
    ]);
    $this->assertDatabaseHas('categories_items', [
      'item_id' => $sellItem->id,
      'category_id' => $category2->id,
    ]);
    $this->assertDatabaseMissing('categories_items', [
      'item_id' => $sellItem->id,
      'category_id' => $category3->id,
    ]);

    // 商品詳細ページ
    $response = $this->actingAs($user)->get('/item/' . $sellItem->id);
    $response->assertStatus(200);

    // 表示確認（商品情報）
    $response->assertSee($sellItem->name);
    $response->assertSee($sellItem->brand);
    $response->assertSee(number_format($sellItem->price));
    $response->assertSee($sellItem->description);
    $response->assertSee($condition->condition_name);
    $response->assertSee('storage/items/' . $sellItem->item_img, false);

    // 表示確認（カテゴリ）
    $response->assertSee($category1->category_name);
    $response->assertSee($category2->category_name);
    $response->assertDontSee($category3->category_name);
  }
}
