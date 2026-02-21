<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'amount' => $this->faker->randomFloat(2, 5, 100),
            'note' => $this->faker->sentence(),
            'warehouse_id' => Warehouse::factory(),
            'user_id' => User::factory(),
        ];
    }
}
