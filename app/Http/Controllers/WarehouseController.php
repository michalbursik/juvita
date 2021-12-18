<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::all();

        return view('warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        //
    }

    public function store(StoreWarehouseRequest $request)
    {
        //
    }

    public function show(Warehouse $warehouse)
    {
        $user = auth()->user();
        if ($user->role === User::ROLE_EMPLOYEE && $user->warehouse_id !== $warehouse->id) {
            return redirect()->route('warehouses.show', [
                'warehouse' => $user->warehouse_id
            ]);
        }

        $currentWarehouse = $warehouse;
        $warehouses = Warehouse::all();

        return view('warehouses.show', compact('currentWarehouse', 'warehouses'));
    }

    public function receipt(Warehouse $warehouse, Product $product)
    {
        $user = Auth::user();

        return view('warehouses.products.receipt', compact('warehouse', 'product', 'user'));
    }

    public function issue(Warehouse $warehouse, Product $product)
    {
        $user = Auth::user();

        return view('warehouses.products.issue', compact('warehouse', 'product', 'user'));
    }

    public function transmission(Warehouse $warehouse, Product $product)
    {
        $warehouseFrom = $warehouse;
        $user = Auth::user();

        $warehouses = Warehouse::all();

        return view('warehouses.products.transmission', compact('warehouseFrom', 'product', 'user', 'warehouses'));
    }

    public function edit(Warehouse $warehouse)
    {
        //
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse)
    {
        //
    }

    public function destroy(Warehouse $warehouse)
    {
        //
    }
}
