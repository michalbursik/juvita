<?php

namespace App\Livewire\Warehouses;

use App\Enums\WarehouseType;
use App\Models\Warehouse;
use App\Services\WarehouseService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Edit extends Component
{
    public Warehouse $warehouse;

    public $name;

    public $type;

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required',
    ];

    public function mount(Warehouse $warehouse)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $this->warehouse = $warehouse;
        $this->name = $warehouse->name;
        $this->type = $warehouse->type->value;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.warehouses.edit', [
            'types' => collect(WarehouseType::cases())
                ->mapWithKeys(fn ($type) => [$type->value => $type->label()])
                ->toArray(),
        ]);
    }

    public function submit(WarehouseService $warehouseService)
    {
        $this->validate();

        $warehouseService->updateWarehouse($this->warehouse, [
            'name' => $this->name,
            'type' => $this->type,
        ]);

        return redirect()->route('warehouses.index');
    }
}
