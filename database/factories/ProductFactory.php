<?php

namespace Database\Factories;

use App\Models\Product;
use App\Traits\SupportsProjections;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    use SupportsProjections;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'origin' => 'CZ',
            'active' => $this->faker->boolean,
            'unit' => $this->faker->randomElement(Product::AVAILABLE_UNITS),
            'order' => 1,
        ];
    }
}
