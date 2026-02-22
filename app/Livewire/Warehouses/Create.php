<?php

namespace App\Livewire\Warehouses;

use App\Enums\WarehouseType;
use App\Services\WarehouseService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Create extends Component
{
    public $name;

    public $type = WarehouseType::TEMPORARY;

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required',
    ];

    #[Layout('layouts.app')]
    public function render()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('livewire.warehouses.create', [
            'types' => collect(WarehouseType::cases())
                ->filter(fn ($type) => !$type->isTrash())
                ->mapWithKeys(fn ($type) => [$type->value => $type->label()])
                ->toArray(),
        ]);
    }

    public function submit(WarehouseService $warehouseService)
    {
        $this->validate();

        $warehouseService->createWarehouse([
            'name' => $this->name,
            'type' => $this->type,
        ]);

        return redirect()->route('warehouses.index');
    }
}
