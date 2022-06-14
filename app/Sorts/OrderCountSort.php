<?php

namespace App\Sorts;

use Illuminate\Database\Eloquent\Builder;

class OrderCountSort implements \Spatie\QueryBuilder\Sorts\Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';

        $query->orderBy('orders_count', $direction);
    }
}