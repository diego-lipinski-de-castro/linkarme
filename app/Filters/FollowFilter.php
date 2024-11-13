<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class FollowFilter implements \Spatie\QueryBuilder\Filters\Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $linkType = $value ? 'DOFOLLOW' : 'NOFOLLOW';

        $query->where('link_type', $linkType);
    }
}
