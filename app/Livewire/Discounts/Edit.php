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

    public $note;

    protected $rules = [
        'warehouseId' => 'required|exists:warehouses,id',
        'amount' => 'required|numeric|min:0.01',
        'note' => 'nullable|string|max:255',
    ];

    public function mount(Discount $discount)
    {
        if (auth()->user()->role->isEmployee()) {
            abort(403);
        }

        if ($discount->status->isApplied()) {
            return redirect()->route('discounts.index')->with('error', 'Použitou slevu již nelze upravovat.');
        }

        $this->discount = $discount;
        $this->warehouseId = $discount->warehouse_id;
        $this->amount = $discount->amount;
        $this->note = $discount->note;
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
        if ($this->discount->status->isApplied()) {
            $this->addError('submit', 'Použitou slevu již nelze upravovat.');

            return;
        }

        $this->validate();

        $this->discount->update([
            'warehouse_id' => $this->warehouseId,
            'amount' => $this->amount,
            'note' => $this->note,
        ]);

        return redirect()->route('discounts.index');
    }

    public function delete()
    {
        $this->discount->delete();

        return redirect()->route('discounts.index');
    }
}
