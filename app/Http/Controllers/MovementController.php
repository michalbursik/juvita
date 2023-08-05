<?php

namespace App\Http\Controllers;

use App\Enums\MovementTypeEnum;
use App\Http\Requests\MoveProductRequest;
use App\Http\Requests\ReceiveProductRequest;
use App\Http\Requests\TrashTransmissionMovementRequest;
use App\Managers\PricesManager;
use App\Managers\WarehouseManager;
use App\Models\Movement;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use App\Repositories\MovementRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MovementController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private readonly MovementRepository $movementRepository) {}

    public function index(Request $request): JsonResponse
    {
        $query = Movement::query();

        $source_warehouse_uuid = $request->input('source_warehouse_uuid');
        $query->when($source_warehouse_uuid, function ($query) use ($source_warehouse_uuid) {
            $query->where('source_warehouse_uuid', $source_warehouse_uuid);
        });

        $target_warehouse_uuid = $request->input('target_warehouse_uuid');
        if (Auth::user()->role === User::ROLE_EMPLOYEE) {
            $target_warehouse_uuid = Auth::user()->warehouse_uuid;
        }
        $query->when($target_warehouse_uuid, function ($query) use ($target_warehouse_uuid) {
            $query->where('target_warehouse_uuid', $target_warehouse_uuid);
        });

        $query->when($request->input('warehouse_uuid'), function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('source_warehouse_uuid', $request->input('warehouse_uuid'))
                    ->orWhere('target_warehouse_uuid', $request->input('warehouse_uuid'));
            });
        });

        $query->when($request->input('product_uuid'), function ($query) use ($request) {
            $query->where('product_uuid', $request->input('product_uuid'));
        });

        $user_uuid = $request->input('user_uuid');
        $query->when($user_uuid, function ($query) use ($user_uuid) {
            $query->where('user_uuid', $user_uuid);
        });

        $query->when($request->input('type'), function ($query) use ($request) {
            $type = MovementTypeEnum::from($request->input('type'));

            $query->where('type', $type);
        });

        $query->when($request->input('day'), function ($query) use ($request) {
            $day = str_replace(' ', '', $request->input('day'));
            $day = Carbon::parse($day);

            $from = $day->clone()->startOfDay();
            $to = $day->clone()->endOfDay();

            $query->whereBetween('created_at', [$from, $to]);
        });

        $movements = $query
            ->orderByDesc('created_at')
            ->paginate($request->input('perPage'), ['*'], 'currentPage');

        return responder()->success($movements)->respond();
    }

//    public function fetchAllAmounts(Request $request): JsonResponse
//    {
//        $warehouse_id = $request->input('warehouse_id');
//
//        if ($warehouse_id === 'trash') {
//            $warehouse = Warehouse::query()->where('type', Warehouse::TYPE_TRASH)->first();
//        } else {
//            $warehouse = Warehouse::query()->find($warehouse_id);
//
//        }
//
//        $query = Movement::query();
//
//        $query->where(function ($query) use ($warehouse) {
//            $query->where('issue_warehouse_id', $warehouse->id)
//                ->orWhere('receipt_warehouse_id', $warehouse->id);
//        });
//
//        // For temporary warehouses show
//        if ($warehouse->type === Warehouse::TYPE_TEMPORARY) {
//            $day = str_replace(' ', '', $request->input('day'));
//            $day = Carbon::parse($day);
//
//            $from = $day->clone()->startOfDay();
//            $to = $day->clone()->endOfDay();
//
//            $query->whereBetween('created_at', [$from, $to]);
//        }
//
//        $movements = $query->get();
//
//        $movementAmounts = $this->calculateMovements($movements, $warehouse->id);
//
//        return responder()->success($movementAmounts)->respond();
//    }

//    private function calculateMovements(Collection $movements, $warehouse_id): array
//    {
//        $result = [];
//
//        $products = Product::all();
//
//        foreach ($products as $product) {
//            $result[$product->id] = [
//                'product_id' => $product->id,
//                'product_name' => $product->name,
//                'amount' => 0
//            ];
//        }
//
//        $movements->each(function (Movement $movement) use (&$result, $warehouse_id) {
//            if ($movement->receipt_warehouse_id === (int) $warehouse_id) {
//                $result[$movement->product_id]['amount'] = round((float) $result[$movement->product_id]['amount'] + (float) $movement->amount, 1);
//            }
//
//            if ($movement->issue_warehouse_id === (int) $warehouse_id) {
//                $result[$movement->product_id]['amount'] = round((float) $result[$movement->product_id]['amount'] - (float) $movement->amount, 1);
//            }
//        });
//
//        return $result;
//    }
}
