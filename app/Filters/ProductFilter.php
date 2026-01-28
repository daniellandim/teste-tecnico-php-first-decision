<?php

namespace App\Filters;

class ProductFilter extends Filters
{
    protected array $filters = [
        'id', 'name', 'description', 'amount', 'quantity',
    ];

    public function name(string $param): void
    {
        $driver = $this->builder->getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            $this->builder->where('name', 'ILIKE', "%{$param}%");
        } else {
            $this->builder->whereRaw(
                'LOWER(name) LIKE ?',
                ['%' . strtolower($param) . '%']
            );
        }
    }

    public function amount(string $param): void
    {
        $this->builder->where('amount', '=', $param);
    }
}
