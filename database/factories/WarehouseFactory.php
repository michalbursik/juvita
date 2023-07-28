<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'type' => $this->faker->randomElement([
                Warehouse::TYPE_MAIN,
                Warehouse::TYPE_TEMPORARY,
                Warehouse::TYPE_INTERNAL,
                Warehouse::TYPE_TRASH,
            ]),
        ];
    }

    public function main(): WarehouseFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Warehouse::TYPE_MAIN
            ];
        });
    }

    public function temporary(): WarehouseFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Warehouse::TYPE_TEMPORARY
            ];
        });
    }

    public function internal(): WarehouseFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Warehouse::TYPE_INTERNAL
            ];
        });
    }

    public function trash(): WarehouseFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Warehouse::TYPE_TRASH
            ];
        });
    }
}
