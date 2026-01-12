<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array{
      return [
        'name'        => $this->faker->word(),
        'price'       => $this->faker->numberBetween(500, 20000),
        'brand'       => $this->faker->word(),
        'description' => $this->faker->realText(50),
        'item_img'    => 'test.jpg',

        // ✅ ここが重要：指定されなければ自動で関連も作る
        'condition_id' => Condition::factory(),
        'sell_user_id' => User::factory(),
      ];
    }

    // 出品者
    public function soldBy(User $user): static{
      return $this->state(fn () => [
        'sell_user_id' => $user->id,
      ]);
    }

    // コンディション
    public function withCondition(Condition $condition): static{
      return $this->state(fn () => [
        'condition_id' => $condition->id,
      ]);
    }
}
