<?php

namespace App\Traits;

use App\Filters\Filters;
use Illuminate\Database\Eloquent\Builder;

trait FilterScope
{
    /**
     * @param Builder $query
     * @param Filters $filters
     * @return mixed
     */
    public function scopeFilters(Builder $query, Filters $filters): mixed
    {
        return $filters->apply($query);
    }
}
