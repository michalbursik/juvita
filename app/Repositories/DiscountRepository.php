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

    public function store(array $data): Discount
    {
        $discount = new Discount($data);

        $discount->save();

        return $discount;
    }

    public function update(Discount $discount, array $data): Discount
    {
        $discount->update($data);

        return $discount;
    }

    public function destroy(Discount $discount): void
    {
        $discount->delete();
    }
}
