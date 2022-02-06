<?php


namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class BaseRepository
{
    public function orderAndPaginate(
        Builder $query, ?int $perPage = null, $columns = null,
        ?string $orderBy = null, ?string $orderByDirection = null
    ): LengthAwarePaginator
    {
        $perPage = $this->getPerPage($perPage);
        $columns = $this->getColumns($columns);
        $orderBy = $this->getOrderBy($orderBy);
        $orderByDirection = $this->getOrderByDirection($orderByDirection);

        $query->orderBy($orderBy, $orderByDirection);

        return $query->paginate(
            $perPage,
            $columns,
            'currentPage'
        );
    }

    private function getPerPage(?int $perPage): ?int
    {
        if (empty($perPage)) {
            // Default is $model->perPage (15)
            $perPage = request()->input('perPage');
        }

        return $perPage;
    }

    private function getColumns($columns): array
    {
        if ($columns !== null && ! is_array($columns)) {
            $columns = [$columns];
        }

        if (empty($columns)) {
            $columns = request()->input('columns', ['*']);
        }

        return $columns;
    }

    private function getOrderBy(?string $orderBy)
    {
        if (empty($orderBy)) {
            $orderBy = request()->input('orderBy', 'created_at');
        }

        return $orderBy;
    }

    private function getOrderByDirection(?string $orderByDirection)
    {
        if (empty($orderByDirection)) {
            $orderByDirection = request()->input('orderByDirection', 'desc');
        }

        return $orderByDirection;
    }
}
