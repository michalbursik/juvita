<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckRequest;
use App\Models\Check;
use App\Models\Price;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use App\Services\DiscountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckController extends Controller
{

    public function __construct(
        private readonly DiscountService $discountService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $query = Check::query();

        $checks = $query
            ->orderByDesc('created_at')
            ->paginate(null, ['*'], 'currentPage');

        return responder()->success($checks)->respond();
    }

    public function fetchAllProductPrices(Request $request): JsonResponse
    {
        $warehouse_uuid = $request->input('warehouse_uuid');
        Log::debug($warehouse_uuid);
        $prices = Price::query()
            ->whereHas('product', function ($query) use ($warehouse_uuid) {
                    $query->where('warehouse_products.warehouse_uuid', $warehouse_uuid)
                        ->where('warehouse_products.total_amount', '>', 0);
                }
            )
            ->where('amount', '>', 0)
            ->get();

        return responder()->success($prices)->with('product')->respond();
    }

    public function store(StoreCheckRequest $request): JsonResponse
    {
        $data = $request->validated();
        /** @var User $user */
        $user = auth()->user();
        $data['user_uuid'] = $user->uuid;
        $data['discount'] = $this->discountService->getTotalDiscount($data['warehouse_uuid']);

        $check = Check::createWithAttributes($data);

        return responder()->success($check)->respond();
    }

    public function show(Check $check): JsonResponse
    {
        return responder()->success($check)->respond();
    }
}
