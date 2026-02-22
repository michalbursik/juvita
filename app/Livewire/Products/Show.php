<?php

namespace App\Livewire\Products;

use App\Models\Movement;
use App\Models\Product;
use App\Models\Warehouse;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public Product $product;

    public Warehouse $warehouse;

    protected $paginationTheme = 'bootstrap';

    public function mount(Warehouse $warehouse, Product $product)
    {
        $this->warehouse = $warehouse;
        $this->product = $product;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $movements = Movement::with(['user', 'issueWarehouse', 'receiptWarehouse'])
            ->where('product_id', $this->product->id)
            ->where(function ($query) {
                $query->where('issue_warehouse_id', $this->warehouse->id)
                    ->orWhere('receipt_warehouse_id', $this->warehouse->id);
            })
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('livewire.products.show', [
            'movements' => $movements,
        ]);
    }
}
