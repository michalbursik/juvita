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
                'movements' => function ($query) {
                    $query->where('movements.created_at', '>=', now()->subDays(7))
                    ->orderByDesc('created_at');
                },
                'products.priceLevels',
                'products' => function ($query) {
                    $query->where('products.active', true)
                            ->orderBy('order');
                },
            ])
            ->respond();
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
}
