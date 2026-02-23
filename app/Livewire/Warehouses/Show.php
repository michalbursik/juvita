<?php

namespace App\Livewire\Warehouses;

use App\Livewire\Traits\HasNumericPad;
use App\Models\Movement;
use App\Models\Warehouse;
use App\Services\WarehouseService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    use HasNumericPad;

    public Warehouse $warehouse;

    public $allWarehouses;

    public $movementAmounts = [];

    public $recentMovements = [];

    public function mount(Warehouse $warehouse, WarehouseService $warehouseService)
    {
        $user = auth()->user();
        if ($user->role === 'employee' && $user->warehouse_id !== $warehouse->id) {
            return redirect()->route('warehouses.show', $user->warehouse_id);
        }

        $this->warehouse = $warehouse->load([
            'products' => function ($query) {
                $query->where('products.active', true)->orderBy('order');
            },
            'movements' => function ($query) {
                $query->where('movements.created_at', '>=', now()->subDays(7))
                    ->orderByDesc('created_at');
            },
        ]);

        $this->allWarehouses = $warehouseService->listWarehouses();

        // Get movement amounts from product_warehouse pivot
        $this->calculateMovementAmounts();
    }

    private function calculateMovementAmounts()
    {
        foreach ($this->warehouse->products as $product) {
            $this->movementAmounts[$product->id] = $product->product_warehouse?->amount ?? 0;
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.warehouses.show');
    }

    public function changeWarehouse($id)
    {
        return redirect()->route('warehouses.show', $id);
    }
}
