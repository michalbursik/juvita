<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
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

    public function index(Request $request): JsonResponse
    {
        $discounts = $this->service->listDiscounts();

        return responder()->success($discounts)->respond();
    }

    public function show(Discount $discount): JsonResponse
    {
        return responder()->success($discount)->respond();
    }

    public function store(StoreDiscountRequest $request): JsonResponse
    {
        $discount = $this->service->createDiscount($request->validated());

        return responder()->success($discount)->respond();
    }

    public function update(UpdateDiscountRequest $request, Discount $discount): JsonResponse
    {
        $discount = $this->service->updateDiscount($discount, $request->validated());

        return responder()->success($discount)->respond();
    }

    public function destroy(Discount $discount): JsonResponse
    {
        $this->service->deleteDiscount($discount);

        return responder()->success()->respond();
    }
}
