<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class FilterLimiter implements \Spatie\QueryBuilder\Filters\Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if(is_array($value)) {

            $from = optional($value)['from'];
            $to = optional($value)['to'];

            if(!blank($from) || !blank($to)) {
                if(blank($from)) {
                    $query->where($property, '<=', $to);
                } elseif(blank($to)) {
                    $query->where($property, '>=', $from);
                } else {
                    $query->where($property, '>=', $from)->where($property, '<=', $to);
                }
            }

        } else {
            $search = explode(' ', $value);

            if (count($search) == 2) {
                $query->where($property, $search[0], $search[1]);
            }

            if (count($search) == 5) {
                if (in_array('&', $search)) {
                    $query->where($property, $search[0], $search[1])->where($property, $search[3], $search[4]);
                } elseif (in_array('||', $search)) {
                    $query->where($property, $search[0], $search[1])->orWhere($property, $search[3], $search[4]);
                }
            }
        }
    }
}
