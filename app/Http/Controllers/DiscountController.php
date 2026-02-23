<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Http\Resources\DiscountResource;
use App\Models\Discount;
use App\Services\DiscountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    private DiscountService $service;

    public function __construct(DiscountService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $discounts = $this->service->listDiscounts();

        return DiscountResource::collection($discounts);
    }

    public function show(Discount $discount)
    {
        $discount->load(['warehouse', 'user']);

        return new DiscountResource($discount);
    }

    public function store(StoreDiscountRequest $request): JsonResponse
    {
        $discount = $this->service->createDiscount($request->validated());

        return (new DiscountResource($discount->load(['warehouse', 'user'])))->response()->setStatusCode(200);
    }

    public function update(UpdateDiscountRequest $request, Discount $discount)
    {
        $discount = $this->service->updateDiscount($discount, $request->validated());

        return new DiscountResource($discount->load(['warehouse', 'user']));
    }

    public function destroy(Discount $discount): JsonResponse
    {
        $this->service->deleteDiscount($discount);

        return response()->json(null, 200);
    }
}
