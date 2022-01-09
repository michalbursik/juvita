<?php

namespace App\Http\Controllers;

use App\Http\Requests\IssueWarehouseMovementRequest;
use App\Http\Requests\TransmissionWarehouseMovementRequest;
use App\Managers\PricesManager;
use App\Managers\WarehouseManager;
use App\Models\PriceLevel;
use App\Models\Warehouse;
use App\Repositories\WarehouseMovementRepository;
use App\Http\Requests\ReceiptWarehouseMovementRequest;
use App\Models\Product;
use App\Models\WarehouseMovement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WarehouseMovementController extends Controller
{
    private WarehouseMovementRepository $repository;
    private PricesManager $pricesManager;
    private WarehouseManager $warehouseManager;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WarehouseMovementRepository $repository,
                                PricesManager $pricesManager,
                                WarehouseManager $warehouseManager)
    {
        $this->repository = $repository;
        $this->pricesManager = $pricesManager;
        $this->warehouseManager = $warehouseManager;
    }

    public function index(Request $request): JsonResponse
    {
        $query = WarehouseMovement::query();

        if ($request->input('product')) {
            $query->where('product_id', $request->input('product'));
        }

        if ($request->input('user')) {
            $query->where('user_id', $request->input('user'));
        }

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

    public function show(WarehouseMovement $warehouseMovement)
    {
        return view('warehouse-movements.show', compact('warehouseMovement'));
    }

    public function issue(IssueWarehouseMovementRequest $request): JsonResponse
    {
        $data = $request->validated();
        $priceLevel = PriceLevel::query()->find($data['price_level_id']);

        $data['price'] = $priceLevel->price;

        $warehouseMovement = $this->repository->store($data);

        $this->warehouseManager->issue($warehouseMovement);
        $this->pricesManager->issue($warehouseMovement, $priceLevel);


        // Receipt on trash warehouse
        $warehouse = Warehouse::query()
            ->where('type', Warehouse::TYPE_TRASH)
            ->first();

        $data['warehouse_id'] = $warehouse->id;
        $data['type'] = WarehouseMovement::TYPE_RECEIPT;

        $warehouseMovementTrash = $this->repository->store($data);

        $this->warehouseManager->receipt($warehouseMovementTrash);
        // On trash warehouse we don't want to manager priceLevels
//        $this->pricesManager->receipt($warehouseMovementTrash);

        return responder()->success($warehouseMovement)->respond();
    }

    public function receipt(ReceiptWarehouseMovementRequest $request): JsonResponse
    {
        $warehouseMovement = $this->repository->store($request->validated());

        $this->warehouseManager->receipt($warehouseMovement);
        $this->pricesManager->receipt($warehouseMovement);

        return responder()->success($warehouseMovement)->respond();
    }

    public function transmission(TransmissionWarehouseMovementRequest $request): JsonResponse
    {
        $data = $request->validated();
        $priceLevel = PriceLevel::query()->find($data['price_level_id']);

        $issue = [
          'type' => WarehouseMovement::TYPE_ISSUE,
          'price' => $priceLevel->price,
          'amount' => $data['amount'],
          'product_id' => $data['product_id'],
          'warehouse_id' => $data['issue_warehouse_id'],
          'user_id' => $data['user_id'],
        ];

        $receipt = [
            'type' => WarehouseMovement::TYPE_RECEIPT,
            'price' => $priceLevel->price,
            'amount' => $data['amount'],
            'product_id' => $data['product_id'],
            'warehouse_id' => $data['receipt_warehouse_id'],
            'user_id' => $data['user_id'],
        ];

        $issueWarehouseMovement = $this->repository->store($issue);
        $this->warehouseManager->issue($issueWarehouseMovement);
        $this->pricesManager->issue($issueWarehouseMovement, $priceLevel);

        $receiptWarehouseMovement = $this->repository->store($receipt);
        $this->warehouseManager->receipt($receiptWarehouseMovement);
//        $this->pricesManager->receipt($receiptWarehouseMovement);

        $warehouseMovements = collect()
            ->push($issueWarehouseMovement, $receiptWarehouseMovement);

        return responder()->success($warehouseMovements)->respond();
    }
}
