<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Payment;


class SelectPaymentMethodTest extends TestCase
{
  use RefreshDatabase;
  
  public function test_select_payment_method(){
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

    // ログイン状態で商品詳細を開く
    $response = $this->actingAs($loginUser)->get('/purchase/' . $item->id);
    $response->assertStatus(200);

    $payment = Payment::create([
      'payment_method' => 'カード払い',

    ]);
  }
}
