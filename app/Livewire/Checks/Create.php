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

    public $search = '';

    public $chosenProducts = []; // format: [price_level_id => amount_after]

    public $loading = false;

    public function mount(WarehouseService $warehouseService)
    {
        $this->warehouses = $warehouseService->listWarehouses();

        if ($this->warehouses->count() > 0) {
            $this->warehouseId = $this->warehouses->first()->id;
            $this->updatedWarehouseId();
        }
    }

    public function updatedWarehouseId()
    {
        $warehouse = Warehouse::with(['priceLevels.product' => function ($q) {
            $q->orderBy('order');
        }])->find($this->warehouseId);

        if ($warehouse) {
            $this->priceLevels = $warehouse->priceLevels->sortBy('product.order');
            $this->discounts = Discount::where('warehouse_id', $this->warehouseId)->get();
        } else {
            $this->priceLevels = collect();
            $this->discounts = collect();
        }
        $this->chosenProducts = [];
    }

    public function getDiscountAmount()
    {
        return collect($this->discounts)->reduce(function ($carry, $discount) {
            return $carry - (float) $discount->amount;
        }, 0.00);
    }

    public function getFilteredPriceLevelsProperty()
    {
        if (empty($this->search)) {
            return $this->priceLevels;
        }

        $term = mb_strtolower($this->search);

        return $this->priceLevels->filter(function ($pl) use ($term) {
            return str_contains(mb_strtolower($pl->product->name), $term);
        });
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
