<?php

namespace App\Http\Controllers;

use App\Enums\WarehouseType;
use App\Http\Resources\ProductResource;
use App\Http\Resources\WarehouseResource;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\WarehouseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    private WarehouseService $service;

    public function __construct(WarehouseService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $warehouses = $this->service->listWarehouses();

        return WarehouseResource::collection($warehouses);
    }

    public function store(Request $request): WarehouseResource
    {
        $data = $request->validate([
            'name' => 'required|string',
        ]);

        $warehouse = $this->service->createWarehouse($data);

        return new WarehouseResource($warehouse);
    }

    public function update(Warehouse $warehouse, Request $request): WarehouseResource
    {
        $data = $request->validate([
            'name' => 'required|string',
        ]);

        $warehouse = $this->service->updateWarehouse($warehouse, $data);

        return new WarehouseResource($warehouse);
    }

    public function destroy(Warehouse $warehouse): JsonResponse
    {
        $this->service->deleteWarehouse($warehouse);

        return response()->json(null, 200);
    }

    public function show(Warehouse $warehouse)
    {
        $user = auth()->user();
        if ($user->role->isEmployee() && $user->warehouse_id !== $warehouse->id) {
            return redirect()->route('warehouses.show', [
                'warehouse' => $user->warehouse_id,
            ]);
        }

        $warehouse->load([
            'movements' => function ($query) {
                $query->where('movements.created_at', '>=', now()->subDays(7))
                    ->orderByDesc('created_at');
            },
            'products.priceLevels',
            'products' => function ($query) {
                $query->where('products.active', true)
                    ->orderBy('order');
            },
        ]);

        return new WarehouseResource($warehouse);
    }

    public function showProduct(Warehouse $warehouse, Product $product): ProductResource
    {
        $p = $warehouse->products()
            ->where('products.id', $product->id)
            ->firstOrFail();

        $p->load(['priceLevels' => function ($query) use ($warehouse) {
            $query->where('warehouse_id', $warehouse->id);
        }]);

        return new ProductResource($p);
    }

    public function receipt(Warehouse $warehouse, Product $product): ProductResource
    {
        return $this->showProduct($warehouse, $product);
    }

    public function issue(Warehouse $warehouse, Product $product): ProductResource
    {
        return $this->showProduct($warehouse, $product);
    }

    public function transmission(Warehouse $warehouse, Product $product): ProductResource
    {
        return $this->showProduct($warehouse, $product);
    }

    public function trash(): JsonResponse
    {
        $warehouse = Warehouse::query()
            ->where('type', WarehouseType::TRASH)
            ->first();

        return responder()->success($warehouse)
            ->with(['movements', 'products' => function ($query) {
                $query->where('products.active', true);
            }])
            ->respond();
    }
}
