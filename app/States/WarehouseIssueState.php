<?php


namespace App\States;


use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;

class WarehouseIssueState
{
    /**
     * @var User
     */
    private User $user;
    /**
     * @var Warehouse
     */
    private Warehouse $warehouse;
    /**
     * @var Product
     */
    private Product $product;
    /**
     * @var float
     */
    private float $amount;

    /**
     * WarehouseIssueState constructor.
     * @param User $user
     * @param Warehouse $warehouse
     * @param Product $product
     * @param float $amount
     */
    public function __construct(User $user, Warehouse $warehouse, Product $product, float $amount)
    {
        $this->user = $user;
        $this->warehouse = $warehouse;
        $this->product = $product;
        $this->amount = $amount;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Warehouse
     */
    public function getWarehouse(): Warehouse
    {
        return $this->warehouse;
    }

    /**
     * @param Warehouse $warehouse
     */
    public function setWarehouse(Warehouse $warehouse): void
    {
        $this->warehouse = $warehouse;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }
}
