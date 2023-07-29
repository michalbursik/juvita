<?php

namespace Database\Factories;

use App\Enums\WarehouseTypeEnum;
use App\Models\Warehouse;
use App\Traits\SupportsProjections;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    use SupportsProjections;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(WarehouseTypeEnum::cases());

        return [
            'name' => $this->faker->name,
            'type' => $type->value,
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
