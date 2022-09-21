<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class NewFilter implements \Spatie\QueryBuilder\Filters\Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where('created_at', '>', now()->subDays(60)->endOfDay());
    }
}
