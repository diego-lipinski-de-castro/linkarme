<?php

namespace App\Filters;

use App\Helper;
use Illuminate\Database\Eloquent\Builder;

class FilterLimiter implements \Spatie\QueryBuilder\Filters\Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $from = Helper::extractNumbersFromString($value['from']);
        $to = Helper::extractNumbersFromString($value['to']);

        $query->where($property, '>=', $from)->where($property, '<=', $to);
    }
}
