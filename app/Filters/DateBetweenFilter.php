<?php

namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class DateBetweenFilter implements \Spatie\QueryBuilder\Filters\Filter
{
    /**
     * @return void
     */
    public function __invoke(Builder $query, $value, string $property)
    {
        $start = Carbon::createFromFormat('d/m/Y', $value[0])->format('Y-m-d');
        $end = Carbon::createFromFormat('d/m/Y', $value[1])->format('Y-m-d');

        if ($start == $end) {
            $query->whereDate($property, $start);
        } else {
            $query->whereBetween($property, [$start, $end]);
        }
    }
}
