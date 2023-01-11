<?php

namespace App\Sorts;

use Illuminate\Database\Eloquent\Builder;

class NewSort implements \Spatie\QueryBuilder\Sorts\Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $query->orderByRaw('dr DESC, da DESC, traffic DESC');
    }
}
