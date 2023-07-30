<?php

namespace App\Http\Controllers;

use App\Http\Requests\MoveProductRequest;
use App\Http\Requests\ReceiveProductRequest;
use App\Models\User;
use App\Models\Warehouse;
use App\Repositories\WarehouseRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WarehouseController extends Controller
{
    private WarehouseRepository $warehouseRepository;

    public function __construct(WarehouseRepository $warehouseRepository)
    {
        $this->warehouseRepository = $warehouseRepository;
    }

    public function index()
    {
        $warehouses = Warehouse::all();

        return responder()->success($warehouses)->respond();
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string'
        ]);

        $warehouse = $this->warehouseRepository->store($data);

        return responder()->success($warehouse)->respond();
    }

    public function update(Warehouse $warehouse, Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string'
        ]);

        $warehouse = $this->warehouseRepository->update($warehouse, $data);

        return responder()->success($warehouse)->respond();
    }

    public function destroy(Warehouse $warehouse): JsonResponse
    {
        $this->warehouseRepository->destroy($warehouse);

        return responder()->success()->respond();
    }

    // Event sourcing
    public function receive(ReceiveProductRequest $request): Response
    {
        $data = $request->validated();
        $warehouse = Warehouse::uuid($data['warehouse_uuid']);
        $warehouse->receiveProduct($data['product_uuid'], $data['price'], $data['amount']);

        return response(null, 202);
    }

    public function move(MoveProductRequest $request): Response
    {
        $data = $request->validated();
        $warehouse = Warehouse::uuid($data['source_warehouse_uuid']);
        $warehouse->moveProduct($data['target_warehouse_uuid'], $data['product_uuid'], $data['price'], $data['amount']);

        return response(null, 202);
    }

    public function totalAmount(Warehouse $warehouse): JsonResponse
    {
        $warehouseProducts = $warehouse->products()->orderBy('id')->get();

        return responder()->success($warehouseProducts)->respond();
    }

    // ------

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
            ->with([
                'movements',
                'products' => function ($query) {
                    $query->where('products.active', true);
                }
            ])
            ->respond();
    }
}
