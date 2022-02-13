<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientAmountException;
use App\Http\Requests\TrashTransmissionMovementRequest;
use App\Http\Requests\TransmissionMovementRequest;
use App\Managers\PricesManager;
use App\Managers\WarehouseManager;
use App\Models\PriceLevel;
use App\Models\User;
use App\Models\Warehouse;
use App\Repositories\MovementRepository;
use App\Http\Requests\ReceiptMovementRequest;
use App\Models\Product;
use App\Models\Movement;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MovementController extends Controller
{
    private MovementRepository $repository;
    private PricesManager $pricesManager;
    private WarehouseManager $warehouseManager;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MovementRepository $repository,
                                PricesManager $pricesManager,
                                WarehouseManager $warehouseManager)
    {
        Log::debug(__FILE__ . ' construct');

        $this->repository = $repository;
        $this->pricesManager = $pricesManager;
        $this->warehouseManager = $warehouseManager;
    }

    public function index(Request $request): JsonResponse
    {
        $query = Movement::query();

        $issue_warehouse_id = $request->input('issue_warehouse_id');
        $query->when($issue_warehouse_id, function ($query) use ($issue_warehouse_id) {
            $query->where('issue_warehouse_id', $issue_warehouse_id);
        });

        $receipt_warehouse_id = $request->input('receipt_warehouse_id');
        if (Auth::user()->role === User::ROLE_EMPLOYEE) {
            $receipt_warehouse_id = Auth::user()->warehouse_id;
        }
        $query->when($receipt_warehouse_id, function ($query) use ($receipt_warehouse_id) {
            $query->where('receipt_warehouse_id', $receipt_warehouse_id);
        });

        $query->when($request->input('warehouse_id'), function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('issue_warehouse_id', $request->input('warehouse_id'))
                    ->orWhere('receipt_warehouse_id', $request->input('warehouse_id'));
            });
        });

        $query->when($request->input('product_id'), function ($query) use ($request) {
            $query->where('product_id', $request->input('product_id'));
        });

        $user_id = $request->input('user_id');
        $query->when($user_id, function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        });

        $query->when($request->input('type'), function ($query) use ($request) {
            $query->where('type', $request->input('type'));
        });

        $movements = $query
            ->orderByDesc('created_at')
            ->paginate($request->input('perPage'), ['*'], 'currentPage');

        return responder()->success($movements)->respond();
    }

    public function fetchAllAmounts(Request $request): JsonResponse
    {
        $warehouse_id = $request->input('warehouse_id');

        if ($warehouse_id === 'trash') {
            $warehouse = Warehouse::query()->where('type', Warehouse::TYPE_TRASH)->first();
        } else {
            $warehouse = Warehouse::query()->find($warehouse_id);

        }

        $query = Movement::query();

        $query->where(function ($query) use ($warehouse) {
            $query->where('issue_warehouse_id', $warehouse->id)
                ->orWhere('receipt_warehouse_id', $warehouse->id);
        });

        // For temporary warehouses show
        if ($warehouse->type === Warehouse::TYPE_TEMPORARY) {
            $day = str_replace(' ', '', $request->input('day'));
            $day = Carbon::parse($day, 'Europe/Prague');

            $from = $day->clone()->startOfDay()->utc();
            $to = $day->clone()->endOfDay()->utc();

            $query->whereBetween('created_at', [$from, $to]);
        }

        $movements = $query->get();

        $movementAmounts = $this->calculateMovements($movements, $warehouse->id);

        return responder()->success($movementAmounts)->respond();
    }

    public function trash(TrashTransmissionMovementRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            $priceLevel = PriceLevel::query()->find($data['price_level_id']);
            $data['price'] = $priceLevel->price;

            // Manage warehouses
            $warehouse = Warehouse::query()
                ->where('type', Warehouse::TYPE_TRASH)
                ->first();

            $data['receipt_warehouse_id'] = $warehouse->id;

            $movement = $this->repository->store($data);

            $this->warehouseManager->transmission($movement, $priceLevel);

            // Manage price levels
            $this->pricesManager->transmission($movement, $priceLevel);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'previous' => $e->getPrevious(),
                'trace' => $e->getTraceAsString(),
            ]);

            return responder()->error(500, $e->getMessage())->respond();
        }

        return responder()->success($movement)->respond();
    }

    public function receipt(ReceiptMovementRequest $request): JsonResponse
    {
        $movement = $this->repository->store($request->validated());

        $this->warehouseManager->receipt($movement);
        $this->pricesManager->receipt($movement);

        return responder()->success($movement)->respond();
    }

    public function transmission(TransmissionMovementRequest $request): JsonResponse
    {
        Log::debug(__FILE__ . '=>' . __METHOD__ . '(' . __LINE__ . ')');

        DB::beginTransaction();

        try {
            Log::debug(__FILE__ . '=>' . __METHOD__ . '(' . __LINE__ . '): try');

            $data = $request->validated();
            Log::debug(__FILE__ . '=>' . __METHOD__ . '(' . __LINE__ . '): validated');

            $priceLevel = PriceLevel::query()->find($data['price_level_id']);
            Log::debug(__FILE__ . '=>' . __METHOD__ . '(' . __LINE__ . '): priceLevel found');

            $data['price'] = $priceLevel->price;

            Log::debug(__FILE__ . '=>' . __METHOD__ . '(' . __LINE__ . '): price set');

            $movement = $this->repository->store($data);

            Log::debug(__FILE__ . '=>' . __METHOD__ . '(' . __LINE__ . '): movements stored');

            $this->warehouseManager->transmission($movement, $priceLevel);

            Log::debug(__FILE__ . '=>' . __METHOD__ . '(' . __LINE__ . '): warehouses changed');

            $this->pricesManager->transmission($movement, $priceLevel);
            Log::debug(__FILE__ . '=>' . __METHOD__ . '(' . __LINE__ . '): price level changed');

            DB::commit();

            Log::debug(__FILE__ . '=>' . __METHOD__ . '(' . __LINE__ . '): transmission created');
        } catch (\Exception $e) {
            Log::debug(__FILE__ . '=>' . __METHOD__ . '(' . __LINE__ . '): exception');
            DB::rollBack();

            Log::debug(__FILE__ . '=>' . __METHOD__ . '(' . __LINE__ . '): exception rollbacked');

            Log::error('Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'previous' => $e->getPrevious(),
                'trace' => $e->getTraceAsString(),
            ]);

            Log::debug(__FILE__ . '=>' . __METHOD__ . '(' . __LINE__ . '): exception logged');

            return responder()->error(500, $e->getMessage())->respond();
        }

        Log::debug(__FILE__ . '=>' . __METHOD__ . '(' . __LINE__ . '): transmission success');

        return responder()->success($movement)->respond();
    }

    private function calculateMovements(Collection $movements, $warehouse_id): array
    {
        $result = [];

        $products = Product::all();

        foreach ($products as $product) {
            $result[$product->id] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'amount' => 0
            ];
        }

        $movements->each(function (Movement $movement) use (&$result, $warehouse_id) {
            if ($movement->receipt_warehouse_id === (int) $warehouse_id) {
                $result[$movement->product_id]['amount'] += $movement->amount;
            }

            if ($movement->issue_warehouse_id === (int) $warehouse_id) {
                $result[$movement->product_id]['amount'] -= $movement->amount;
            }
        });

        return $result;
    }
}
