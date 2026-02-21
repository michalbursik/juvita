<?php

namespace App\Http\Controllers;

use App\DTOs\CheckDTO;
use App\Http\Requests\StoreCheckRequest;
use App\Services\WarehouseService;
use App\Models\Check;
use App\Models\Discount;
use App\Models\Movement;
use App\Models\PriceLevel;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $query = $warehouse
            ->products()
            ->where('product_warehouse.amount', '>', 0);

        $products = $query
            ->orderByDesc('order')
            ->get();

        return responder()
            ->success($products)
            ->with([
                'priceLevels' => function ($query) use ($warehouse_id) {
                    $query->where('warehouse_id', $warehouse_id);
                }
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

    /**
     * @throws \App\Exceptions\InsufficientAmountException
     */
    private function applyCheck(Check $check)
    {
        /** @var Product $checkProduct */
        foreach ($check->products as $checkProduct) {
            $data = [
                'type' => Movement::TYPE_CHECK,
                'amount' => $checkProduct->product_check->amount_before - $checkProduct->product_check->amount_after,
                'price' => $checkProduct->product_check->price,
                'product_id' => $checkProduct->id,
                'issue_warehouse_id' => $check->warehouse_id,
                'receipt_warehouse_id' => null,
                'user_id' => $check->user_id,
            ];

            $movement = new Movement($data);
            $movement->save();

            $priceLevel = PriceLevel::query()->find($checkProduct->product_check->price_level_id);

            $this->warehouseManager->issue($movement, $priceLevel);
            $this->pricesManager->issue($movement, $priceLevel);
        }
    }
}
