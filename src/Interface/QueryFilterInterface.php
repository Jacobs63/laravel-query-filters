<?php

namespace CoderaWorks\LaravelQueryFilters\Interface;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;

interface QueryFilterInterface
{
    public function pattern(): string;

    public function valid(string $tag, mixed $value, mixed $data): bool;

    public function apply(Builder|EloquentBuilder $query, string $tag, mixed $value, mixed $data): void;
}
