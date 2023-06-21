<?php

namespace CoderaWorks\LaravelqueryFilters\Examples\Filter;

use CoderaWorks\LaravelQueryFilters\Interface\QueryFilterInterface;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;

class ExampleFilter implements QueryFilterInterface
{
    public function pattern(): string
    {
        return 'example-filter';
    }

    public function valid(string $tag, mixed $value, mixed $data): bool
    {
        return true;
    }

    public function apply(Builder|EloquentBuilder $query, string $tag, mixed $value, mixed $data): void
    {
        $query->where('example_field', $value);
    }
}
