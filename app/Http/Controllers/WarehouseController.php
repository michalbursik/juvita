<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Models\PriceLevel;
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

        $query = Warehouse::query();

        if (Auth::user()->role === User::ROLE_EMPLOYEE) {
            $query->whereIn('type', [Warehouse::TYPE_MAIN, Warehouse::TYPE_TEMPORARY]);
        }

        $warehouses = $query->get();

        $priceLevels = PriceLevel::query()
            ->where('validTo', '>=', now()->toDateTime())
            ->get();

        return view('warehouses.products.transmission',
            compact('warehouseFrom', 'product', 'user', 'warehouses', 'priceLevels')
        );
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
