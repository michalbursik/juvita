<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\Warehouse;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $name;

    public $origin;

    public $unit = 'kg';

    public $active = true;

    public $order = 10;

    public $image;

    protected $rules = [
        'name' => 'required|string|max:255',
        'origin' => 'nullable|string|max:255',
        'unit' => 'required|in:kg,ks',
        'active' => 'boolean',
        'order' => 'required|integer',
        'image' => 'nullable|image|max:1024',
    ];

    public function mount()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $lastProduct = Product::orderByDesc('order')->first();
        $this->order = $lastProduct ? $lastProduct->order + 10 : 10;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.products.create');
    }

    public function submit()
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }

        $product = Product::create([
            'name' => $this->name,
            'origin' => $this->origin,
            'unit' => $this->unit,
            'active' => $this->active,
            'order' => $this->order,
            'image' => $imagePath,
        ]);

        // When a product is created, we need to sync it with all warehouses with 0 amount
        $warehouses = Warehouse::all();
        foreach ($warehouses as $warehouse) {
            $warehouse->products()->attach($product->id, [
                'amount' => 0,
                'price' => 0,
            ]);
        }

        return redirect()->route('products.index');
    }
}
