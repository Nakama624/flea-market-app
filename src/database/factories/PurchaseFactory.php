<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\User;
use App\Models\Item;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
  protected $model = Purchase::class;

  public function definition(): array
  {
    return [
      'user_id' => User::factory(),
      'item_id' => Item::factory(),
      'payment_id' => Payment::factory(),
      'delivery_postcode' => '111-2222',
      'delivery_address'  => '東京都八王子市111',
      'delivery_building' => 'テストビル101',
      'status' => 'pending',
    ];
  }
}
