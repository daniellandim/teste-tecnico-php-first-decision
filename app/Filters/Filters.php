<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class Filters
{
    protected Request $request;
    protected Builder $builder;
    protected array $filters = [];

    /**
     * Filters constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param $builder
     * @return mixed
     */
    public function apply($builder): mixed
    {

        $this->builder = $builder;
        foreach ($this->getFilters() as $filter => $value) {
            if ($this->methodExistsFor($filter)) {
                $method = method_exists($this, $filter) ? $filter : Str::camel($filter);
                $this->$method($value);
            }
        }
        return $this->builder;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->request->only($this->filters);
    }

    /**
     * @param $filter
     * @return bool
     */
    private function methodExistsFor($filter): bool
    {
        return method_exists($this, $filter) || method_exists($this, Str::camel($filter));
    }
}
