<?php

namespace App\Sorts;

use Illuminate\Database\Eloquent\Builder;

class RecommendedSort implements \Spatie\QueryBuilder\Sorts\Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $query->orderByRaw('orders_count DESC, dr DESC, da DESC, traffic DESC');
    }
}
