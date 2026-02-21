<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class CheckFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'discount' => $this->faker->randomFloat(2, 0, 100),
            'warehouse_id' => Warehouse::factory(),
            'user_id' => User::factory(),
        ];
    }
}
