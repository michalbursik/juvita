<?php

namespace App\Http\Controllers;

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
//        $this->middleware('auth');
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
        $warehouseMovement = $this->repository->store($request->validated());

        $this->updateWarehouse($warehouseMovement);

        // When issuing, what if there is not enough ?

        return redirect()->route('warehouses.show', [
            'warehouse' => $warehouseMovement->warehouse->id
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
}
