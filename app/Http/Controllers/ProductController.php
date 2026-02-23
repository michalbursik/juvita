<?php

namespace App\Http\Controllers;

use App\DTOs\ProductDTO;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = Product::query();

        $warehouse_id = $request->input('priceLevels.warehouse_id');

        $query->when($warehouse_id, function ($query) use ($warehouse_id) {
            $query->whereHas('priceLevels', function ($q) use ($warehouse_id) {
                $q->where('warehouse_id', $warehouse_id);
            });
        });

        $products = $query
            ->orderBy($request->input('orderBy', 'created_at'))
            ->paginate($request->input('perPage'), ['*'], 'currentPage');

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        $product->load(['movements', 'priceLevels']);

        return new ProductResource($product);
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->service->createProduct(
            ProductDTO::fromRequest($request->validated())
        );

        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->service->updateProduct(
            $product,
            ProductDTO::fromRequest($request->validated())
        );

        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $this->service->deleteProduct($product);

        return response()->json(null, 200);
    }

    public function nextOrder()
    {
        $product = Product::query()
            ->orderByDesc('order')
            ->first();

        if (empty($product)) {
            $order = 10;
        } else {
            $order = $product->order + 10;
        }

        return response()->json(['data' => ['order' => $order]]);
    }
}
