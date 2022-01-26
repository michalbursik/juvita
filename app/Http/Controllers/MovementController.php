<?php

namespace App\Http\Controllers;

use App\Http\Requests\IssueMovementRequest;
use App\Http\Requests\TransmissionMovementRequest;
use App\Managers\PricesManager;
use App\Managers\WarehouseManager;
use App\Models\PriceLevel;
use App\Models\Warehouse;
use App\Repositories\MovementRepository;
use App\Http\Requests\ReceiptMovementRequest;
use App\Models\Product;
use App\Models\Movement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        $query->when($request->input('product'), function ($query) use ($request) {
            $query->where('product_id', $request->input('product'));
        });

        $query->when($request->input('user'), function ($query) use ($request) {
            $query->where('user_id', $request->input('user'));
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
            ->paginate(null, ['*'], 'currentPage');

        return responder()->success($movements)->respond();
    }

    public function trash(IssueMovementRequest $request): JsonResponse
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
        $this->warehouseManager->issue($movement);

        // Manage price levels
        $this->pricesManager->issue($movement, $priceLevel);

//        On trash warehouse we don't want to manager priceLevels
//        $this->pricesManager->receipt($movementTrash);

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

        $this->warehouseManager->transmission($movement);
        $this->pricesManager->transmission($movement, $priceLevel);

        return responder()->success($movement)->respond();
    }
}
