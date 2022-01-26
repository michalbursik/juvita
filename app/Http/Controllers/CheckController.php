<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckRequest;
use App\Models\Check;
use App\Models\Movement;
use App\Models\Product;
use App\Models\Warehouse;
use App\Repositories\CheckRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckController extends Controller
{
    private CheckRepository $repository;

    public function __construct(CheckRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request): JsonResponse
    {
        $query = Check::query();

        $checks = $query
            ->orderByDesc('created_at')
            ->paginate(null, ['*'], 'currentPage');

        return responder()->success($checks)->respond();
    }

    public function store(StoreCheckRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $check = $this->repository->store($data);

        $warehouse = Warehouse::query()->find($data['warehouse_id']);

        foreach ($data['products'] as $productData) {
            $product = Product::query()->find($productData['id']);

            $oldProduct = $warehouse
                ->products()
                ->where('id', $product->id)
                ->first();

            $check->products()->save($product, [
                'amount_before' => $oldProduct->product_warehouse->amount,
                'amount_after' => $productData['amount'],
                'price_level_id' => null
            ]);
        }

        $this->applyCheck($check);

        return responder()->success($check)->respond();
    }

    private function applyCheck(Check $check)
    {
        // Dont update warehouse
        // Rather create issue or check movement

        $movement = new Movement();

        /** @var Warehouse $warehouse */
        $warehouse = $check->warehouse;

        /** @var  $checkProduct */
        foreach ($check->products as $checkProduct) {
            /** @var Product $warehouseProduct */
            $warehouseProduct = $warehouse->products()
                ->where('id', $checkProduct->id)
                ->first();

            $warehouseProduct->product_warehouse->amount = $checkProduct->product_check->amount_after;
            $warehouseProduct->product_warehouse->save();
        }
    }
}
