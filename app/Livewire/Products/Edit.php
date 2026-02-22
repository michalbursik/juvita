<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Product $product;

    public $name;

    public $origin;

    public $unit;

    public $active;

    public $order;

    public $image;

    public $existingImage;

    protected $rules = [
        'name' => 'required|string|max:255',
        'origin' => 'nullable|string|max:255',
        'unit' => 'required|in:kg,ks',
        'active' => 'boolean',
        'order' => 'required|integer',
        'image' => 'nullable|image|max:1024',
    ];

    public function mount(Product $product)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $this->product = $product;
        $this->name = $product->name;
        $this->origin = $product->origin;
        $this->unit = $product->unit;
        $this->active = (bool) $product->active;
        $this->order = $product->order;
        $this->existingImage = $product->image;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.products.edit');
    }

    public function submit()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'origin' => $this->origin,
            'unit' => $this->unit,
            'active' => $this->active,
            'order' => $this->order,
        ];

        if ($this->image) {
            if ($this->existingImage) {
                Storage::disk('public')->delete($this->existingImage);
            }
            $data['image'] = $this->image->store('products', 'public');
        }

        $this->product->update($data);

        return redirect()->route('products.index');
    }
}
