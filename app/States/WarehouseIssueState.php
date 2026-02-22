<?php

namespace App\States;

use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;

class WarehouseIssueState
{
    private User $user;

    private Warehouse $warehouse;

    private Product $product;

    private float $amount;

    /**
     * WarehouseIssueState constructor.
     */
    public function __construct(User $user, Warehouse $warehouse, Product $product, float $amount)
    {
        $this->user = $user;
        $this->warehouse = $warehouse;
        $this->product = $product;
        $this->amount = $amount;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getWarehouse(): Warehouse
    {
        return $this->warehouse;
    }

    public function setWarehouse(Warehouse $warehouse): void
    {
        $this->warehouse = $warehouse;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }
}
