<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;

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
        return view('warehouses.show', compact('warehouse'));
    }

    public function receipt(Warehouse $warehouse, Product $product)
    {
        $user = User::query()->first(); // Auth::user();

        return view('warehouses.products.receipt', compact('warehouse', 'product', 'user'));
    }

    public function issue(Warehouse $warehouse, Product $product)
    {
        $user = User::query()->first(); // Auth::user();

        return view('warehouses.products.issue', compact('warehouse', 'product', 'user'));
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
