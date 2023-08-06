<?php


namespace App\Repositories;


use App\Models\Discount;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DiscountRepository extends BaseRepository
{
    public function all(): LengthAwarePaginator
    {
        $query = Discount::query();

        return $this->orderAndPaginate($query);
    }

    public function store(array $attributes): Discount
    {
        $discount = new Discount($attributes);
        $discount->writeable()->save();

        return $discount;
    }

    public function update(Discount $discount, array $attributes): Discount
    {
        $discount->writeable()->update($attributes);

        return $discount->fresh();
    }

    public function destroy(Discount $discount): void
    {
        $discount->writeable()->delete();
    }
}
