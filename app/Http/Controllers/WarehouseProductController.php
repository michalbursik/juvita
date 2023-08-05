<?php

namespace App\Http\Controllers;

use App\Enums\WarehouseTypeEnum;
use App\Events\ProductMoved;
use App\Events\ProductTrashed;
use App\Http\Requests\MoveProductRequest;
use App\Http\Requests\ReceiveProductRequest;
use App\Http\Requests\TrashProductRequest;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use App\Repositories\WarehouseRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class WarehouseProductController extends Controller
{
    public function __construct(public readonly WarehouseRepository $warehouseRepository) {}

    public function show(WarehouseProduct $warehouseProduct): JsonResponse
    {
        return responder()->success($warehouseProduct)->with('prices')->respond();
    }

    public function receive(ReceiveProductRequest $request): Response
    {
        $data = $request->validated();
        $warehouse = Warehouse::uuid($data['warehouse_uuid']);
        $warehouse->receiveProduct($data['product_uuid'], $data['price'], $data['amount']);

        return response(null, 202);
    }

    public function move(MoveProductRequest $request): Response
    {
        /** @var User $user */
        $user = auth()->user();
        $data = $request->validated();

        event(new ProductMoved(
            $data['source_warehouse_uuid'],
            $data['target_warehouse_uuid'],
            $data['product_uuid'],
            $user->uuid,
            $data['price_uuid'],
            $data['amount'],
        ));

        return response(null, 202);
    }


    public function trash(TrashProductRequest $request): Response
    {
        $data = $request->validated();
        /** @var User $user */
        $user = auth()->user();

        $trashWarehouse = $this->warehouseRepository->getTrashWarehouse();

        /** @var WarehouseProduct $trashWarehouseProduct */
        $trashWarehouseProduct = $trashWarehouse->products()
            ->where('product_uuid', $data['product_uuid'])
            ->first();

        event(new ProductTrashed(
            $data['warehouse_product_uuid'],
            $trashWarehouseProduct->uuid,
            $user->uuid,
            $data['price_uuid'],
            $data['amount']
        ));

        return response(null, 202);
    }

    public function totalAmount(Warehouse $warehouse): JsonResponse
    {
        $warehouseProducts = $warehouse->products()->orderBy('id')->get();

        return responder()->success($warehouseProducts)->respond();
    }
}
