<?php

namespace App\Http\Controllers;

use App\Http\Requests\MoveProductRequest;
use App\Http\Requests\ReceiveProductRequest;
use App\Http\Requests\TrashProductRequest;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use App\Repositories\WarehouseRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WarehouseController extends Controller
{
    private WarehouseRepository $warehouseRepository;

    public function __construct(WarehouseRepository $warehouseRepository)
    {
        $this->warehouseRepository = $warehouseRepository;
    }

    public function index(): JsonResponse
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

    public function show(Warehouse $warehouse): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->role === User::ROLE_EMPLOYEE && $user->warehouse_uuid !== $warehouse->uuid) {
            return redirect()->route('warehouses.show', [
                'warehouse' => $user->warehouse_uuid
            ]);
        }

        return responder()->success($warehouse)
            ->with([
                'products' => function ($query) {
                    $query->orderBy('id');
                }
//                'movements' => function ($query) {
//                    $query->where('movements.created_at', '>=', now()->subDays(7))
//                        ->orderByDesc('created_at');
//                },
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
                // 'movements',
                'products'
            ])
            ->respond();
    }
}
