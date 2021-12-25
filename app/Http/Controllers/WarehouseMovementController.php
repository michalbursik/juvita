<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransmissionWarehouseMovementRequest;
use App\Managers\PricesManager;
use App\Managers\WarehouseManager;
use App\Models\Warehouse;
use App\Repositories\WarehouseMovementRepository;
use App\Http\Requests\StoreWarehouseMovementRequest;
use App\Models\Product;
use App\Models\WarehouseMovement;
use Illuminate\Http\RedirectResponse;

class WarehouseMovementController extends Controller
{
    private WarehouseMovementRepository $repository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WarehouseMovementRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // List of all products
        // For each product count current status

        $products = Product::all();

//        foreach ($products as $product) {
//            $product->amount = $this->calculateAmount($product);
//        }

        return view('storage-status.index', compact('products'));
    }

//    private function calculateAmount(Product $product)
//    {
//        /** @var Warehouse $storage */
//        $storage = Warehouse::query()->find(1);
//
//        $product = $storage->products()->where('products.id', $product->id)->first();
//
//        return $product->pivot->amount;
//    }

    public function show(WarehouseMovement $warehouseMovement)
    {
        return view('warehouse-movements.show', compact('warehouseMovement'));
    }

    public function store(StoreWarehouseMovementRequest $request): RedirectResponse
    {
        // TODO issue prices not handled!
        $warehouseMovement = $this->repository->store($request->validated());

        $this->updatePrices($warehouseMovement);
        $this->updateWarehouse($warehouseMovement);


        if ($request->input('type') === WarehouseMovement::TYPE_ISSUE) {
            $data = $request->validated();

            // Receipt on trash warehouse
            $warehouse = Warehouse::query()
                ->where('type', Warehouse::TYPE_TRASH)
                ->first();

            $data['warehouse_id'] = $warehouse->id;
            $data['type'] = WarehouseMovement::TYPE_RECEIPT;

            $warehouseMovement = $this->repository->store($data);

            $this->updatePrices($warehouseMovement);
            $this->updateWarehouse($warehouseMovement);
        }

        return redirect()->route('warehouses.show', [
            'warehouse' => $warehouseMovement->warehouse->id
        ]);
    }

    public function transmission(TransmissionWarehouseMovementRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $issue = [
          'type' => WarehouseMovement::TYPE_ISSUE,
          'amount' => $data['amount'],
          'product_id' => $data['product_id'],
          'warehouse_id' => $data['issue_warehouse_id'],
          'user_id' => $data['user_id'],
        ];

        $receipt = [
            'type' => WarehouseMovement::TYPE_RECEIPT,
            'amount' => $data['amount'],
            'product_id' => $data['product_id'],
            'warehouse_id' => $data['receipt_warehouse_id'],
            'user_id' => $data['user_id'],
        ];

        $issueWarehouseMovement = $this->repository->store($issue);
        $this->updateWarehouse($issueWarehouseMovement);

        $receiptWarehouseMovement = $this->repository->store($receipt);
        $this->updateWarehouse($receiptWarehouseMovement);

        return redirect()->route('warehouses.show', [
            'warehouse' => $issueWarehouseMovement->warehouse->id
        ]);
    }

    // TODO event ?
    private function updateWarehouse(WarehouseMovement $warehouseMovement)
    {
        $warehouseManager = new WarehouseManager($warehouseMovement->warehouse);

        $warehouseManager->{$warehouseMovement->type}(
            $warehouseMovement->product,
            $warehouseMovement->amount
        );
    }

    private function updatePrices(WarehouseMovement $warehouseMovement)
    {
        $pricesManager = new PricesManager();

        $pricesManager->{$warehouseMovement->type}(
          $warehouseMovement->product,
          $warehouseMovement->price
        );
    }
}
