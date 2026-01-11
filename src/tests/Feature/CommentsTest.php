<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;

class CommentsTest extends TestCase
{

  use RefreshDatabase;

  // ログイン済みのユーザーはコメントを送信できる
  public function test_logged_in_user_can_post_comment_and_comment_count_increases(){
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

    $commentItem = Item::create([
      'name' => '商品A',
      'price' => 1000,
      'brand' => 'ブランドA',
      'condition_id' => $condition->id,
      'description' => '説明A',
      'item_img' => 'test.jpg',
      'sell_user_id' => $loginUser->id,
    ]);

    // 事前は0件（増加テストのため）
    $this->assertDatabaseMissing('comments', [
      'item_id' => $commentItem->id,
      'user_id' => $loginUser->id,
    ]);

    // コメントを送信
    $res = $this->actingAs($loginUser)
      ->from('/item/' . $commentItem->id)
      ->post('/item/' . $commentItem->id, [
          'comment' => 'コメント追加テスト',
      ]);
    $res->assertRedirect('/item/' . $commentItem->id);
    // DBに登録されていることを確認
    $this->assertDatabaseHas('comments', [
      'item_id' => $commentItem->id,
      'user_id' => $loginUser->id,
      'comment' => 'コメント追加テスト',
    ]);

    // ログイン状態で商品詳細を開く
    $response = $this->actingAs($loginUser)->get('/item/' . $commentItem->id);
    $response->assertStatus(200);

    // コメント数
    $response->assertSee('コメント(1)');
  }

  // ログイン前のユーザーはコメントを送信できない
  public function test_unlogged_in_user_cannot_post_comment(){
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

    $commentItem = Item::create([
      'name' => '商品A',
      'price' => 1000,
      'brand' => 'ブランドA',
      'condition_id' => $condition->id,
      'description' => '説明A',
      'item_img' => 'test.jpg',
      'sell_user_id' => $loginUser->id,
    ]);

    // 事前は0件（増加テストのため）
    $this->assertDatabaseMissing('comments', [
      'item_id' => $commentItem->id,
      'user_id' => $loginUser->id,
    ]);

    // コメントを送信
    $res = $this->from('/item/' . $commentItem->id)
      ->post('/item/' . $commentItem->id, [
          'comment' => 'コメント追加テスト',
      ]);

    // 未ログインならログイン画面へ
    $res->assertRedirect('/login');

    // DBに登録されていることを確認
    $this->assertDatabaseMissing('comments', [
      'item_id' => $commentItem->id,
      'user_id' => $loginUser->id,
      'comment' => 'コメント追加テスト',
    ]);

    // ログイン状態で商品詳細を開く
    $response = $this->get('/item/' . $commentItem->id);
    $response->assertStatus(200);

    // コメント数増加なし
    $response->assertSee('コメント(0)');
  }

  // コメントが入力されていない場合、バリデーションメッセージが表示される
  public function test_comment_required(){
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

    $commentItem = Item::create([
      'name' => '商品A',
      'price' => 1000,
      'brand' => 'ブランドA',
      'condition_id' => $condition->id,
      'description' => '説明A',
      'item_img' => 'test.jpg',
      'sell_user_id' => $loginUser->id,
    ]);

    $response = $this->actingAs($loginUser)
      ->from('/item/' . $commentItem->id)
      ->post('/item/' . $commentItem->id, [
          'comment' => '',
    ]);

    $response->assertRedirect('/item/' . $commentItem->id);
    $response->assertSessionHasErrors(['comment']);

    // DBに保存されていない
    $this->assertDatabaseMissing('comments', [
      'item_id' => $commentItem->id,
      'user_id' => $loginUser->id,
      'comment' => '',
    ]);
  }

  // コメントが255字以上の場合、バリデーションメッセージが表示される
  public function test_comment_max_255(){
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

    $commentItem = Item::create([
      'name' => '商品A',
      'price' => 1000,
      'brand' => 'ブランドA',
      'condition_id' => $condition->id,
      'description' => '説明A',
      'item_img' => 'test.jpg',
      'sell_user_id' => $loginUser->id,
    ]);

    $tooLong = str_repeat('a', 256);

    $response = $this->actingAs($loginUser)
      ->from('/item/' . $commentItem->id)
      ->post('/item/' . $commentItem->id, [
            'comment' => $tooLong,
    ]);

    $response->assertRedirect('/item/' . $commentItem->id);
    $response->assertSessionHasErrors(['comment']);

    // DBに保存されていない
    $this->assertDatabaseMissing('comments', [
      'item_id' => $commentItem->id,
      'user_id' => $loginUser->id,
      'comment' => $tooLong,
    ]);
  }
}
