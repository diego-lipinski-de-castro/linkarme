<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class FilterLimiter implements \Spatie\QueryBuilder\Filters\Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $search = explode(' ', $value);

        if(count($search) == 2) {
            $query->where($property, $search[0], $search[1]);
        }

        if(count($search) == 5) {

            if(in_array('&', $search)) {
                $query->where($property, $search[0], $search[1])->where($property, $search[3], $search[4]);
            } elseif(in_array('||', $search)) {
                $query->where($property, $search[0], $search[1])->orWhere($property, $search[3], $search[4]);
            }
        }
    }
}