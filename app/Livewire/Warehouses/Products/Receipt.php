<?php

namespace App\Livewire\Warehouses\Products;

use App\DTOs\MovementDTO;
use App\Livewire\Traits\HasNumericPad;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\WarehouseService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Receipt extends Component
{
    use HasNumericPad;

    public Warehouse $warehouse;

    public Product $product;

    public $amount = 0;

    public $price = 0;

    public $loading = false;

    public function mount(Warehouse $warehouse, Product $product)
    {
        $this->warehouse = $warehouse;
        $this->product = $product;

        // Find the current price for this product in this warehouse
        $wp = $warehouse->products()->find($product->id);
        if ($wp && $wp->product_warehouse) {
            $this->price = $wp->product_warehouse->price;
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.warehouses.products.receipt');
    }

    public function submit(WarehouseService $warehouseService)
    {
        $this->loading = true;

        $dto = new MovementDTO(
            productId: $this->product->id,
            amount: (float) $this->amount,
            userId: auth()->id(),
            receiptWarehouseId: $this->warehouse->id,
            price: (float) $this->price,
            type: 'receipt'
        );

        try {
            $warehouseService->receiveStock($dto);

            return redirect()->route('warehouses.show', $this->warehouse->id);
        } catch (\Exception $e) {
            $this->addError('submit', $e->getMessage());
            $this->loading = false;
        }
    }
}
