<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PriceLevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'amount' => $this->faker->randomFloat(1, 10, 100),
            'price' => $this->faker->randomFloat(1, 1, 50),
            'validFrom' => now(),
            'validTo' => now()->addYear(),
            'status' => \App\Models\PriceLevel::STATUS_ACTIVE,
            'product_id' => \App\Models\Product::factory(),
            'warehouse_id' => \App\Models\Warehouse::factory(),
        ];
    }
}
