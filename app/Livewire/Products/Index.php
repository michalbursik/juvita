<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Layout('layouts.app')]
    public function render()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('livewire.products.index', [
            'products' => Product::orderBy('order')->paginate(20),
        ]);
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
    }
}
