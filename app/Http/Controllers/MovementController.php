<?php

namespace App\Http\Controllers;

use App\DTOs\MovementDTO;
use App\Enums\WarehouseType;
use App\Http\Requests\ReceiptMovementRequest;
use App\Http\Requests\TransmissionMovementRequest;
use App\Http\Requests\TrashTransmissionMovementRequest;
use App\Models\Movement;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\WarehouseService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MovementController extends Controller
{
    private WarehouseService $warehouseService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }

    public function index(Request $request): JsonResponse
    {
        $query = Movement::query();

        $issue_warehouse_id = $request->input('issue_warehouse_id');
        if ($issue_warehouse_id === 'trash') {
            $trashWarehouse = Warehouse::query()->where('type', WarehouseType::TRASH)->first();
            $issue_warehouse_id = $trashWarehouse?->id;
        }
        $query->when($issue_warehouse_id, function ($query) use ($issue_warehouse_id) {
            $query->where('issue_warehouse_id', $issue_warehouse_id);
        });

        $receipt_warehouse_id = $request->input('receipt_warehouse_id');
        if ($receipt_warehouse_id === 'trash') {
            $trashWarehouse = Warehouse::query()->where('type', WarehouseType::TRASH)->first();
            $receipt_warehouse_id = $trashWarehouse?->id;
        }
        if (Auth::user()->role === User::ROLE_EMPLOYEE) {
            $receipt_warehouse_id = Auth::user()->warehouse_id;
        }
        $query->when($receipt_warehouse_id, function ($query) use ($receipt_warehouse_id) {
            $query->where('receipt_warehouse_id', $receipt_warehouse_id);
        });

        $warehouse_id = $request->input('warehouse_id');
        if ($warehouse_id === 'trash') {
            $trashWarehouse = Warehouse::query()->where('type', WarehouseType::TRASH)->first();
            $warehouse_id = $trashWarehouse?->id;
        }
        $query->when($warehouse_id, function ($query) use ($warehouse_id) {
            $query->where(function ($query) use ($warehouse_id) {
                $query->where('issue_warehouse_id', $warehouse_id)
                    ->orWhere('receipt_warehouse_id', $warehouse_id);
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

    public function fetchAllAmounts(Request $request): JsonResponse
    {
        $warehouse_id = $request->input('warehouse_id');

        if ($warehouse_id === 'trash') {
            $warehouse = Warehouse::query()->where('type', WarehouseType::TRASH)->first();
        } else {
            $warehouse = Warehouse::query()->find($warehouse_id);

        }

        $query = Movement::query();

        $query->where(function ($query) use ($warehouse) {
            $query->where('issue_warehouse_id', $warehouse->id)
                ->orWhere('receipt_warehouse_id', $warehouse->id);
        });

        // For temporary warehouses show
        if ($warehouse->type->isTemporary()) {
            $day = str_replace(' ', '', $request->input('day'));
            $day = Carbon::parse($day);

            $from = $day->clone()->startOfDay();
            $to = $day->clone()->endOfDay();

            $query->whereBetween('created_at', [$from, $to]);
        }

        $movements = $query->get();

        $movementAmounts = $this->calculateMovements($movements, $warehouse->id);

        return responder()->success($movementAmounts)->respond();
    }

    public function trash(TrashTransmissionMovementRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            // Manage warehouses
            $warehouse = Warehouse::query()
                ->where('type', WarehouseType::TRASH)
                ->firstOrFail();

            $data['receipt_warehouse_id'] = $warehouse->id;

            $dto = MovementDTO::fromRequest($data);

            $this->warehouseService->transferStock($dto);

            $movement = Movement::query()
                ->where('product_id', $dto->productId)
                ->where('issue_warehouse_id', $dto->issueWarehouseId)
                ->where('receipt_warehouse_id', $dto->receiptWarehouseId)
                ->where('user_id', $dto->userId)
                ->latest()
                ->first();

            return responder()->success($movement)->respond();
        } catch (\Exception $e) {
            Log::error('Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return responder()->error(500, $e->getMessage())->respond();
        }
    }

    public function receipt(ReceiptMovementRequest $request): JsonResponse
    {
        $dto = MovementDTO::fromRequest($request->validated());

        $this->warehouseService->receiveStock($dto);

        $movement = Movement::query()
            ->where('product_id', $dto->productId)
            ->where('receipt_warehouse_id', $dto->receiptWarehouseId)
            ->where('user_id', $dto->userId)
            ->latest()
            ->first();

        return responder()->success($movement)->respond();
    }

    public function transmission(TransmissionMovementRequest $request): JsonResponse
    {
        try {
            $dto = MovementDTO::fromRequest($request->validated());

            $this->warehouseService->transferStock($dto);

            $movement = Movement::query()
                ->where('product_id', $dto->productId)
                ->where('issue_warehouse_id', $dto->issueWarehouseId)
                ->where('receipt_warehouse_id', $dto->receiptWarehouseId)
                ->where('user_id', $dto->userId)
                ->latest()
                ->first();

            return responder()->success($movement)->respond();
        } catch (\Exception $e) {
            Log::error('Exception', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return responder()->error(500, $e->getMessage())->respond();
        }
    }

    private function calculateMovements(Collection $movements, $warehouse_id): array
    {
        $result = [];

        $products = Product::all();

        foreach ($products as $product) {
            $result[$product->id] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'amount' => 0,
            ];
        }

        $movements->each(function (Movement $movement) use (&$result, $warehouse_id) {
            if ($movement->receipt_warehouse_id === $warehouse_id) {
                $result[$movement->product_id]['amount'] = round((float) $result[$movement->product_id]['amount'] + (float) $movement->amount, 1);
            }

            if ($movement->issue_warehouse_id === $warehouse_id) {
                $result[$movement->product_id]['amount'] = round((float) $result[$movement->product_id]['amount'] - (float) $movement->amount, 1);
            }
        });

        return $result;
    }
}
