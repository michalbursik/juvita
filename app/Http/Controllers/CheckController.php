<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckRequest;
use App\Managers\PricesManager;
use App\Managers\WarehouseManager;
use App\Models\Check;
use App\Models\Discount;
use App\Models\Movement;
use App\Models\PriceLevel;
use App\Models\Product;
use App\Models\Warehouse;
use App\Repositories\CheckRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckController extends Controller
{
    private CheckRepository $repository;
    private WarehouseManager $warehouseManager;
    private PricesManager $pricesManager;

    public function __construct(CheckRepository $repository,
                                WarehouseManager $warehouseManager,
                                PricesManager $pricesManager)
    {
        $this->repository = $repository;
        $this->warehouseManager = $warehouseManager;
        $this->pricesManager = $pricesManager;
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
        DB::beginTransaction();

        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();

            // Process all discounts
            $discounts = Discount::all();

            $data['discount'] = $discounts->reduce(function ($carry, Discount $discount) {
                return $carry - (float) $discount->amount;
            }, 0);

            foreach ($discounts as $discount) {
                $discount->delete();
            }

            $check = $this->repository->store($data);

            foreach ($data['products'] as $productData) {
                $product = Product::query()->find($productData['product_id']);

                $priceLevel = PriceLevel::query()->find($productData['price_level_id']);

                $check->products()->save($product, [
                    'amount_before' => (double) $priceLevel->amount,
                    'amount_after' => (double) $productData['amount'],
                    'price_level_id' => $priceLevel->id,
                    'price' => (double) $priceLevel->price,
                ]);

                Log::debug('', [
                    'check' => $check,
                    'product' => $product,
                    'priceLevel' => $priceLevel,
                    'productData' => $productData,
                ]);
            }

            $this->applyCheck($check);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            return responder()->error($exception->getCode(), $exception->getMessage())->respond();
        }

        return responder()->success($check)->respond();
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
                'amount' => (double) $checkProduct->product_check->amount_before - (double) $checkProduct->product_check->amount_after,
                'price' => (double) $checkProduct->product_check->price,
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
