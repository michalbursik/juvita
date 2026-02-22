<?php

namespace App\Livewire\Discounts;

use App\Models\Discount;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $query = Discount::with(['warehouse', 'user'])->orderByDesc('created_at');

        if (auth()->user()->role === 'employee') {
            $query->where('warehouse_id', auth()->user()->warehouse_id);
        }

        return view('livewire.discounts.index', [
            'discounts' => $query->get(),
        ]);
    }
}
