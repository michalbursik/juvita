<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Models\Discount;
use App\Repositories\DiscountRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    private DiscountRepository $discountRepository;

    public function __construct(DiscountRepository $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $discounts = $this->discountRepository->all();

        return responder()->success($discounts)->respond();
    }

    public function show(Discount $discount): JsonResponse
    {
        return responder()->success($discount)->respond();
    }

    public function store(StoreDiscountRequest $request): JsonResponse
    {
        $attributes = $request->validated();

        $discount = Discount::createWithAttributes($attributes);

        return responder()->success($discount)->respond();
    }

    public function update(UpdateDiscountRequest $request, Discount $discount): JsonResponse
    {
        $discount = $discount->modify($request->validated());

        return responder()->success($discount)->respond();
    }

    public function destroy(Discount $discount): JsonResponse
    {
        $discount->remove();

        return responder()->success()->respond();
    }
}
