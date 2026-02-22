<?php

namespace App\Livewire\Discounts;

use App\Models\Discount;
use App\Models\Warehouse;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Edit extends Component
{
    public Discount $discount;

    public $warehouseId;

    public $amount;

    protected $rules = [
        'warehouseId' => 'required|exists:warehouses,id',
        'amount' => 'required|numeric|min:0.01',
    ];

    public function mount(Discount $discount)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $this->discount = $discount;
        $this->warehouseId = $discount->warehouse_id;
        $this->amount = $discount->amount;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.discounts.edit', [
            'warehouses' => Warehouse::orderBy('name')->get(),
        ]);
    }

    public function submit()
    {
        $this->validate();

        $this->discount->update([
            'warehouse_id' => $this->warehouseId,
            'amount' => $this->amount,
        ]);

        return redirect()->route('discounts.index');
    }

    public function delete()
    {
        $this->discount->delete();

        return redirect()->route('discounts.index');
    }
}
