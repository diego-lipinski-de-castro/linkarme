<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class SuggestionFilter implements \Spatie\QueryBuilder\Filters\Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $op = $value == '1' ? '>' : '<';

        $query->whereColumn('suggested', $op, 'sale');
    }
}
