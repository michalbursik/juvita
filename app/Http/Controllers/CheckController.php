<?php

namespace App\Http\Controllers;

use App\DTOs\CheckDTO;
use App\Http\Requests\StoreCheckRequest;
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

    public function index(Request $request): JsonResponse
    {
        $query = Check::query();

        $checks = $query
            ->orderByDesc('created_at')
            ->paginate(null, ['*'], 'currentPage');

        return responder()->success($checks)->respond();
    }

    public function fetchAllProducts(Request $request): JsonResponse
    {
        $warehouse_id = $request->input('warehouse_id');

        $warehouse = Warehouse::find($warehouse_id);

        $products = $warehouse
            ->products()
            ->where('product_warehouse.amount', '>', 0)
            ->orderByDesc('order')
            ->get();

        return responder()
            ->success($products)
            ->with([
                'priceLevels' => function ($query) use ($warehouse_id) {
                    $query->where('warehouse_id', $warehouse_id);
                },
            ])
            ->respond();
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

            return responder()->error(500, $exception->getMessage())->respond();
        }

        return responder()->success()->respond();
    }

    public function show(Check $check): JsonResponse
    {
        return responder()->success($check)->respond();
    }

}
