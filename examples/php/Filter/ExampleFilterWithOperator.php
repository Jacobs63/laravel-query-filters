<?php

namespace CoderaWorks\LaravelqueryFilters\Examples\Filter;

use CoderaWorks\LaravelQueryFilters\Interface\QueryFilterInterface;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ExampleFilterWithOperator implements QueryFilterInterface
{
    public function pattern(): string
    {
        return 'example-filter-with-operator';
    }

    public function valid(string $tag, mixed $value, mixed $data): bool
    {
        if (! is_array($data)) {
            return false;
        }

        return Validator::make([
            'value' => $value,
            'operator' => $data['operator'] ?? null,
        ], [
            'value' => ['required', 'numeric'],
            'operator' => [
                'required',
                Rule::in([
                    '>', '>=', '=', '<', '<=', '!=', '<>', 'like'
                ])
            ]
        ])->passes();
    }

    public function apply(Builder|EloquentBuilder $query, string $tag, mixed $value, mixed $data): void
    {
        if (! is_array($data)) {
            return;
        }

        $query->where('example_field', $data['operator'], $value);
    }
}
