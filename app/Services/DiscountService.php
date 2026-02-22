<?php

namespace App\Services;

use App\Models\Discount;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DiscountService
{
    public function listDiscounts(): LengthAwarePaginator
    {
        return Discount::query()
            ->orderByDesc('created_at')
            ->paginate(request()->input('perPage'), ['*'], 'currentPage');
    }

    public function createDiscount(array $data): Discount
    {
        return Discount::create($data);
    }

    public function updateDiscount(Discount $discount, array $data): Discount
    {
        $discount->update($data);

        return $discount;
    }

    public function deleteDiscount(Discount $discount): void
    {
        $discount->delete();
    }
}
