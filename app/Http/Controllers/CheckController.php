<?php

namespace App\Http\Controllers;

use App\DTOs\CheckDTO;
use App\Http\Requests\StoreCheckRequest;
use App\Http\Resources\CheckResource;
use App\Http\Resources\ProductResource;
use App\Models\Check;
use App\Models\Warehouse;
use App\Services\WarehouseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckController extends Controller
{
    private WarehouseService $warehouseService;

    public function __construct(WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }

    public function index(Request $request)
    {
        $checks = Check::query()
            ->orderByDesc('created_at')
            ->paginate(null, ['*'], 'currentPage');

        return CheckResource::collection($checks);
    }

    public function fetchAllProducts(Request $request)
    {
        $warehouse_id = $request->input('warehouse_id');

        $warehouse = Warehouse::findOrFail($warehouse_id);

        $products = $warehouse
            ->products()
            ->where('product_warehouse.amount', '>', 0)
            ->orderByDesc('order')
            ->get();

        $products->load(['priceLevels' => function ($query) use ($warehouse_id) {
            $query->where('warehouse_id', $warehouse_id);
        }]);

        return ProductResource::collection($products);
    }

    public function store(StoreCheckRequest $request): JsonResponse
    {
        try {
            $this->warehouseService->checkInventory(
                CheckDTO::fromRequest($request->validated())
            );
        } catch (\Exception $exception) {
            Log::error('Exception', [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);

            return response()->json(['message' => $exception->getMessage()], 500);
        }

        return response()->json(null, 200);
    }

    public function show(Check $check)
    {
        $check->load(['warehouse', 'user', 'products']);

        return new CheckResource($check);
    }
}
