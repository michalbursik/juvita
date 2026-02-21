<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'active' => true,
            'order' => $this->faker->numberBetween(1, 1000),
            'unit' => \App\Models\Product::DEFAULT_UNIT,
        ];
    }
}
