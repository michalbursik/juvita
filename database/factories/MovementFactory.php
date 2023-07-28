<?php

namespace Database\Factories;

use App\Enums\WarehouseTypeEnum;
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
    public function definition(): array
    {
        return [
            'amount' => 100,
            'price' => 10,
            'type' => $this->faker->randomElement(WarehouseTypeEnum::cases()),
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'receipt_warehouse_id' => Warehouse::factory(),
            'issue_warehouse_id' => Warehouse::factory(),
        ];
    }

    public function check(): MovementFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Movement::TYPE_CHECK
                // TODO warehouse ?
            ];
        });
    }

    public function issue(Warehouse $issueWarehouse): MovementFactory
    {
        return $this->state(function (array $attributes) use ($issueWarehouse) {
            return [
                'type' => Movement::TYPE_ISSUE,
                'issue_warehouse_id' => $issueWarehouse->id,
                // 'receipt_warehouse_id' => null // not null??
            ];
        });
    }

    public function receiptTo(Warehouse $receiptWarehouse): MovementFactory
    {
        return $this->state(function (array $attributes) use ($receiptWarehouse) {
            return [
                'type' => Movement::TYPE_RECEIPT,
                'receipt_warehouse_id' => $receiptWarehouse->id,
                'issue_warehouse_id' => null,
            ];
        });
    }

    public function transmission(Warehouse $issueWarehouse, Warehouse $receiptWarehouse): MovementFactory
    {
        return $this->state(function (array $attributes) use ($issueWarehouse, $receiptWarehouse) {
            return [
                'type' => Movement::TYPE_TRANSMISSION,
                'issue_warehouse_id' => $issueWarehouse->id,
                'receipt_warehouse_id' => $receiptWarehouse->id
            ];
        });
    }
}
