<?php

namespace Database\Factories;

use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConditionFactory extends Factory
{
  protected $model = Condition::class;

  public function definition(): array{
    return [
      'condition_name' => $this->faker->randomElement([
        'test良好', 
        'test目立った傷や汚れなし', 
        'testやや傷や汚れあり'
      ]),
    ];
  }
}
