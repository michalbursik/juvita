<?php

namespace App\Livewire\Warehouses\Products;

use App\Enums\WarehouseType;
use App\DTOs\MovementDTO;
use App\Livewire\Traits\HasNumericPad;
use App\Models\PriceLevel;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\WarehouseService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Transmission extends Component
{
    use HasNumericPad;

    public Warehouse $warehouse;

    public Product $product;

    public $allWarehouses;

    public $priceLevels = [];

    public $amount = 0;

    public $priceLevelId;

    public $issueWarehouseId;

    public $receiptWarehouseId;

    public $loading = false;

    public function mount(Warehouse $warehouse, Product $product)
    {
        $this->warehouse = $warehouse;
        $this->product = $product;
        $this->allWarehouses = Warehouse::all();

        $this->issueWarehouseId = Warehouse::where('type', WarehouseType::MAIN)->first()?->id ?? $warehouse->id;

        $userWarehouseId = auth()->user()->warehouse_id;
        $this->receiptWarehouseId = $userWarehouseId;
        if (! $this->receiptWarehouseId || $this->receiptWarehouseId === $this->issueWarehouseId) {
            $this->receiptWarehouseId = Warehouse::where('type', WarehouseType::TEMPORARY)->first()?->id;
        }

        $this->fetchPriceLevels();
    }

    public function fetchPriceLevels()
    {
        $this->priceLevels = PriceLevel::where('warehouse_id', $this->issueWarehouseId)
            ->where('product_id', $this->product->id)
            ->where('amount', '>', 0)
            ->get();

        if ($this->priceLevels->count() > 0) {
            $this->priceLevelId = $this->priceLevels->first()->id;
        } else {
            $this->priceLevelId = null;
        }
    }

    public function updatedIssueWarehouseId()
    {
        $this->fetchPriceLevels();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.warehouses.products.transmission');
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

        if (! $this->issueWarehouseId || ! $this->receiptWarehouseId || ! $this->priceLevelId) {
            $this->addError('submit', 'Musíte vyplnit všechny údaje.');
            $this->loading = false;

            return;
        }

        if ($this->issueWarehouseId === $this->receiptWarehouseId) {
            $this->addError('receiptWarehouseId', 'Sklady musí být rozdílné.');
            $this->loading = false;

            return;
        }

        $maxAmount = $this->getMaxAmount();
        if ((float) $this->amount > (float) $maxAmount) {
            $this->addError('amount', 'Množství nemůže být větší než skladová zásoba.');
            $this->loading = false;

            return;
        }

        $dto = new MovementDTO(
            productId: $this->product->id,
            amount: (float) $this->amount,
            userId: auth()->id(),
            receiptWarehouseId: $this->receiptWarehouseId,
            issueWarehouseId: $this->issueWarehouseId,
            priceLevelId: $this->priceLevelId,
            type: 'transmission'
        );

        try {
            $warehouseService->transferStock($dto);

            return redirect()->route('warehouses.show', $this->warehouse->id);
        } catch (\Exception $e) {
            $this->addError('submit', $e->getMessage());
            $this->loading = false;
        }
    }
}
