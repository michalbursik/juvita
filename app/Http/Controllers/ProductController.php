<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Warehouse;
use App\Repositories\ProductRepository;
use Flugg\Responder\Serializers\NoopSerializer;
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
                'priceLevels'
            ])
            ->respond();
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->repository->store($request->validated());

        $warehouses = Warehouse::all();

        foreach ($warehouses as $warehouse) {
            $warehouse->products()->save($product, [
                'amount' => 0,
                'price' => 0.00,
            ]);
        }

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
