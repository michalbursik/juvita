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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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


        if ($request->input('search')) {
            // ->type
            // ->count
            // ->price
            // ->created_at

            // ->product->name
            // ->user->name

//            $query->where();
        }


        $movements = $query
            ->orderByDesc('created_at')
            ->paginate($request->input('perPage'), ['*'], 'currentPage');

        return responder()->success($movements)->respond();
    }

    public function fetchAllAmounts(Request $request): JsonResponse
    {
        $warehouse_id = $request->input('warehouse_id');
        $day = Carbon::parse($request->input('day'));

        $query = Movement::query();

        $query->where(function ($query) use ($warehouse_id) {
            $query->where('issue_warehouse_id', $warehouse_id)
                ->orWhere('receipt_warehouse_id', $warehouse_id);
        });

        $query->whereDate('created_at', $day->toDateString());

        $movements = $query->get();

        $movementAmounts = $this->calculateMovements($movements);

        return responder()->success($movementAmounts)->respond();
    }

    public function trash(TrashTransmissionMovementRequest $request): JsonResponse
    {
        $data = $request->validated();
        $priceLevel = PriceLevel::query()->find($data['price_level_id']);
        $data['price'] = $priceLevel->price;

        // Manage warehouses
        $warehouse = Warehouse::query()
            ->where('type', Warehouse::TYPE_TRASH)
            ->first();

        $data['receipt_warehouse_id'] = $warehouse->id;

        $movement = $this->repository->store($data);

        try {
            $this->warehouseManager->transmission($movement);
        } catch (InsufficientAmountException $e) {
            $this->repository->destroy($movement);

            return responder()->error($e->getCode(), $e->getMessage())->respond();
        }

        // Manage price levels
        $this->pricesManager->transmission($movement, $priceLevel);

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
        $data = $request->validated();
        $priceLevel = PriceLevel::query()->find($data['price_level_id']);

        $data['price'] = $priceLevel->price;

        $movement = $this->repository->store($data);

        try {
            $this->warehouseManager->transmission($movement);
        } catch (InsufficientAmountException $e) {
            $this->repository->destroy($movement);

            return responder()->error($e->getCode(), $e->getMessage())->respond();
        }

        $this->pricesManager->transmission($movement, $priceLevel);

        return responder()->success($movement)->respond();
    }

    private function calculateMovements(Collection $movements): array
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

        $movements->each(function (Movement $movement) use (&$result) {
            if ($movement->receipt_warehouse_id) {
                $result[$movement->product_id]['amount'] += $movement->amount;
            }

            if ($movement->type === Movement::TYPE_CHECK && $movement->issue_warehouse_id) {
                $result[$movement->product_id]['amount'] -= $movement->amount;
            }
        });

        return $result;
    }
}
