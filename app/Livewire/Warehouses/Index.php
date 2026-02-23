<?php

namespace App\Livewire\Warehouses;

use App\Models\Warehouse;
use App\Services\WarehouseService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    public $warehouses;

    public function mount(WarehouseService $warehouseService)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('warehouses.show', auth()->user()->warehouse_id);
        }

        $this->warehouses = $warehouseService->listWarehouses(includeInactive: true);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.warehouses.index');
    }

    public function deleteWarehouse($id, WarehouseService $warehouseService)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouseService->deleteWarehouse($warehouse);
        $this->warehouses = $warehouseService->listWarehouses(includeInactive: true);
    }

    public function toggleActive($id, WarehouseService $warehouseService)
    {
        if (auth()->user()->role->isEmployee()) {
            abort(403);
        }

        $warehouse = Warehouse::findOrFail($id);
        $warehouseService->updateWarehouse($warehouse, [
            'active' => ! $warehouse->active,
        ]);
        $this->warehouses = $warehouseService->listWarehouses(includeInactive: true);
    }
}
