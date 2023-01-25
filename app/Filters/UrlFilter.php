<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class UrlFilter implements \Spatie\QueryBuilder\Filters\Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $value = Str::contains($value, '://') ?
            str_replace('www.', '', parse_url($value, PHP_URL_HOST)) :
            str_replace('www.', '', parse_url($value, PHP_URL_PATH));

        if(Str::endsWith($value, '/')) {
            $value = substr($value, 0, -1);
        }

        $query->where('url', 'like', "%$value%");
    }
}
