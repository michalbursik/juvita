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
use Illuminate\Http\RedirectResponse;

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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Product::all();

        return view('storage-status.index', compact('products'));
    }

    public function show(WarehouseMovement $warehouseMovement)
    {
        return view('warehouse-movements.show', compact('warehouseMovement'));
    }

    public function issue(IssueWarehouseMovementRequest $request): RedirectResponse
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

        return redirect()->route('warehouses.show', [
            'warehouse' => $warehouseMovement->warehouse->id
        ]);
    }

    public function receipt(ReceiptWarehouseMovementRequest $request): RedirectResponse
    {
        $warehouseMovement = $this->repository->store($request->validated());

        $this->warehouseManager->receipt($warehouseMovement);
        $this->pricesManager->receipt($warehouseMovement);

        return redirect()->route('warehouses.show', [
            'warehouse' => $warehouseMovement->warehouse->id
        ]);
    }

    public function transmission(TransmissionWarehouseMovementRequest $request): RedirectResponse
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


        return redirect()->route('warehouses.show', [
            'warehouse' => $issueWarehouseMovement->warehouse->id
        ]);
    }
}
