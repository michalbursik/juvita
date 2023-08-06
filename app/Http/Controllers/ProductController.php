<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request): JsonResponse
    {
        $query = Product::query();

        $products = $query
            ->orderBy($request->input('orderBy', 'created_at'))
            ->paginate($request->input('perPage'), ['*'], 'currentPage');

        return responder()->success($products)->respond();
    }

    public function show(Product $product): JsonResponse
    {
        return responder()->success($product)->respond();
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->repository->store($request->validated());

        return responder()->success($product)->respond();
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $this->repository->update($product, $request->validated());

        return responder()->success($product)->respond();
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
