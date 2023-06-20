<?php

namespace CoderaWorks\LaravelQueryFilters\Tests\TestClasses\Filter;

use CoderaWorks\LaravelQueryFilters\Interface\QueryFilterInterface;
use CoderaWorks\LaravelQueryFilters\Tests\TestClasses\Enum\QueryFiltersTestFiltersEnum;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;

class QueryFilterRegisteringTestQueryFilter2Interface implements QueryFilterInterface
{
    public function pattern(): string
    {
        return QueryFiltersTestFiltersEnum::TEST_FILTER_2->value;
    }

    public function valid(string $tag, mixed $value, mixed $data): bool
    {
        return true;
    }

    public function apply(Builder|EloquentBuilder $query, string $tag, mixed $value, mixed $data): void
    {
    }
}
