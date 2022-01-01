<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Warehouse;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{

    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $products = Product::all();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $productOrder = Product::query()
            ->orderByDesc('order')
            ->first()
            ->order + 10;

        return view('products.create', compact('productOrder'));
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->repository->store($request->validated());

        $warehouses = Warehouse::all();

        foreach ($warehouses as $warehouse) {
            $warehouse->products()->save($product, [
                'amount' => 0,
                'price' => 0.00,
            ]);
        }

        return redirect()->route('products.index');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->repository->update($product, $request->validated());

        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        //
    }
}
