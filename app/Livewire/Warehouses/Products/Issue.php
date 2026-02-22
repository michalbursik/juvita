<?php

namespace App\Livewire\Warehouses\Products;

use App\DTOs\MovementDTO;
use App\Enums\WarehouseType;
use App\Livewire\Traits\HasNumericPad;
use App\Models\PriceLevel;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\WarehouseService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Issue extends Component
{
    use HasNumericPad;

    public Warehouse $warehouse;

    public Product $product;

    public $priceLevels;

    public $amount = 0;

    public $priceLevelId;

    public $loading = false;

    public function mount(Warehouse $warehouse, Product $product)
    {
        $this->warehouse = $warehouse;
        $this->product = $product;

        $this->priceLevels = PriceLevel::where('warehouse_id', $warehouse->id)
            ->where('product_id', $product->id)
            ->where('amount', '>', 0)
            ->get();

        if ($this->priceLevels->count() > 0) {
            $this->priceLevelId = $this->priceLevels->first()->id;
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.warehouses.products.issue');
    }

    public function getMaxAmount()
    {
        if (! $this->priceLevelId) {
            return 0;
        }
        $pl = $this->priceLevels->firstWhere('id', $this->priceLevelId);

        return $pl ? $pl->amount : 0;
    }

    public function submit(WarehouseService $warehouseService)
    {
        $this->loading = true;

        if (! $this->priceLevelId) {
            $this->addError('submit', 'Musíte vybrat cenovou hladinu.');
            $this->loading = false;

            return;
        }

        $maxAmount = $this->getMaxAmount();
        if ((float) $this->amount > (float) $maxAmount) {
            $this->addError('amount', 'Množství nemůže být větší než skladová zásoba.');
            $this->loading = false;

            return;
        }

        $trashWarehouseId = Warehouse::where('type', WarehouseType::TRASH)->firstOrFail()->id;

        $dto = new MovementDTO(
            productId: $this->product->id,
            amount: (float) $this->amount,
            userId: auth()->id(),
            receiptWarehouseId: $trashWarehouseId,
            issueWarehouseId: $this->warehouse->id,
            priceLevelId: $this->priceLevelId,
            type: 'trash'
        );

        try {
            $warehouseService->transferStock($dto); // Issue in this app is implemented as transfer to trash

            return redirect()->route('warehouses.show', $this->warehouse->id);
        } catch (\Exception $e) {
            $this->addError('submit', $e->getMessage());
            $this->loading = false;
        }
    }
}
