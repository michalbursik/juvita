<?php

namespace Database\Factories;

use App\Models\Movement;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => Movement::TYPE_RECEIPT,
            'amount' => $this->faker->randomFloat(1, 1, 100),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'receipt_warehouse_id' => Warehouse::factory(),
        ];
    }
}
