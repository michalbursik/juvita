<?php

namespace App\Livewire\Discounts;

use App\Models\Discount;
use App\Models\Warehouse;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Create extends Component
{
    public $warehouseId;

    public $amount;

    protected $rules = [
        'warehouseId' => 'required|exists:warehouses,id',
        'amount' => 'required|numeric|min:0.01',
    ];

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.discounts.create', [
            'warehouses' => Warehouse::orderBy('name')->get(),
        ]);
    }

    public function submit()
    {
        $this->validate();

        Discount::create([
            'warehouse_id' => $this->warehouseId,
            'amount' => $this->amount,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('discounts.index');
    }
}
