<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Models\PriceLevel;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use App\Transformers\WarehouseTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::all();

        return responder()->success($warehouses)->respond();
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

        return responder()->success($warehouse)
            ->with([
                'movements',
                'products' => function ($query) {
                    $query->where('products.active', true);
                },
                'products.priceLevels'
            ])
            ->respond();
    }

//    public function receipt(Warehouse $warehouse, Product $product)
//    {
//        $user = Auth::user();
//
//        $product = $warehouse->products()->where('product_id', $product->id)->first();
//
//        return view('warehouses.products.receipt', compact('warehouse', 'product', 'user'));
//    }

//    public function issue(Warehouse $warehouse, Product $product)
//    {
//        $user = Auth::user();
//
//        $priceLevels = PriceLevel::query()
//            ->where('product_id', $product->id)
//            ->get();
//
//        return view('warehouses.products.issue', compact('warehouse', 'product', 'user', 'priceLevels'));
//    }

//    public function transmission(Warehouse $warehouse, Product $product)
//    {
//        $warehouseFrom = $warehouse;
//        $user = Auth::user();
//
//        $query = Warehouse::query();
//
//        if (Auth::user()->role === User::ROLE_EMPLOYEE) {
//            $query->whereIn('type', [Warehouse::TYPE_MAIN, Warehouse::TYPE_TEMPORARY]);
//        }
//
//        $warehouses = $query->get();
//
//        $priceLevels = PriceLevel::query()
////            ->where('validTo', '>=', now()->toDateTime())
//            ->where('product_id', $product->id)
//            ->get();
//
//        $product = $warehouse->products()->where('product_id', $product->id)->first();
//
//        return view('warehouses.products.transmission',
//            compact('warehouseFrom', 'product', 'user', 'warehouses', 'priceLevels')
//        );
//    }

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

    /**
     * @return JsonResponse
     */
    public function trash(): JsonResponse
    {
        $warehouse = Warehouse::query()
            ->where('type', Warehouse::TYPE_TRASH)
            ->first();

        return responder()->success($warehouse)
            ->with(['movements', 'products' => function ($query) {
                $query->where('products.active', true);
            }])
            ->respond();
    }

//    public function showProduct(Warehouse $warehouse, Product $product)
//    {
//        $user = auth()->user();
//        if ($user->role === User::ROLE_EMPLOYEE && $user->warehouse_id !== $warehouse->id) {
//            return redirect()->route('warehouses.show', [
//                'warehouse' => $user->warehouse_id
//            ]);
//        }
//
//        $currentWarehouse = $warehouse;
//        $movements = $warehouse
//            ->movements()
//            ->where('product_id', $product->id)
//            ->get();
//
//        return view('warehouses.products.show', compact('currentWarehouse', 'movements', 'product'));
//    }
}
