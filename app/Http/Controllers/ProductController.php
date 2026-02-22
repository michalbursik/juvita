<?php

namespace App\Http\Controllers;

use App\DTOs\ProductDTO;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        $query = Product::query();

        $warehouse_id = $request->input('priceLevels.warehouse_id');

        $query->when($warehouse_id, function ($query) use ($warehouse_id) {
            $query->where('priceLevels.warehouse_id', $warehouse_id);
        });

        $products = $query
            ->orderBy($request->input('orderBy', 'created_at'))
            ->paginate($request->input('perPage'), ['*'], 'currentPage');

        return responder()->success($products)->respond();
    }

    public function show(Product $product): JsonResponse
    {
        return responder()->success($product)
            ->with([
                'movements',
                'priceLevels',
            ])
            ->respond();
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->service->createProduct(
            ProductDTO::fromRequest($request->validated())
        );

        return responder()->success($product)->respond();
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $this->service->updateProduct(
            $product,
            ProductDTO::fromRequest($request->validated())
        );

        return responder()->success($product)->respond();
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->service->deleteProduct($product);

        return responder()->success()->respond();
    }

    public function nextOrder(): JsonResponse
    {
        $product = Product::query()
            ->orderByDesc('order')
            ->first();

        if (empty($product)) {
            $order = 10;
        } else {
            $order = $product->order + 10;
        }

        return responder()->success(['order' => $order])->respond();
    }
}
