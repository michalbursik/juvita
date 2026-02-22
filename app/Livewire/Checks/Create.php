<?php

namespace App\Livewire\Checks;

use App\DTOs\CheckDTO;
use App\Models\Discount;
use App\Models\PriceLevel;
use App\Models\Warehouse;
use App\Services\WarehouseService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Create extends Component
{
    public $warehouseId;

    public $warehouses;

    public $discounts = [];

    public $priceLevels = [];

    public $chosenProducts = []; // format: [price_level_id => amount_after]

    public $loading = false;

    public function mount()
    {
        $this->warehouses = Warehouse::with(['priceLevels.product' => function ($q) {
            $q->orderBy('order');
        }])->get();

        if ($this->warehouses->count() > 0) {
            $this->warehouseId = $this->warehouses->first()->id;
            $this->updatedWarehouseId();
        }
    }

    public function updatedWarehouseId()
    {
        $warehouse = $this->warehouses->firstWhere('id', $this->warehouseId);
        if ($warehouse) {
            $this->priceLevels = $warehouse->priceLevels->sortBy('product.order');
            $this->discounts = Discount::where('warehouse_id', $this->warehouseId)->get();
        } else {
            $this->priceLevels = [];
            $this->discounts = [];
        }
        $this->chosenProducts = [];
    }

    public function getDiscountAmount()
    {
        return collect($this->discounts)->reduce(function ($carry, $discount) {
            return $carry - (float) $discount->amount;
        }, 0.00);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.checks.create');
    }

    public function submit(WarehouseService $warehouseService)
    {
        $this->loading = true;

        if (empty($this->chosenProducts)) {
            $this->addError('submit', 'Musíte zadat alespoň jeden produkt.');
            $this->loading = false;

            return;
        }

        $productsData = [];
        foreach ($this->chosenProducts as $plId => $amount) {
            if ($amount === '' || $amount === null) {
                continue;
            }

            $pl = PriceLevel::find($plId);
            if ($pl) {
                $productsData[] = [
                    'product_id' => $pl->product_id,
                    'price_level_id' => $pl->id,
                    'amount' => (float) $amount,
                ];
            }
        }

        if (empty($productsData)) {
            $this->addError('submit', 'Musíte zadat platná množství.');
            $this->loading = false;

            return;
        }

        $dto = new CheckDTO(
            warehouseId: $this->warehouseId,
            userId: auth()->id(),
            products: $productsData
        );

        try {
            $warehouseService->checkInventory($dto);

            return redirect()->route('checks.index');
        } catch (\Exception $e) {
            $this->addError('submit', $e->getMessage());
            $this->loading = false;
        }
    }
}
