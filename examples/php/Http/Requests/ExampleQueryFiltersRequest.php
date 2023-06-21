<?php

namespace CoderaWorks\LaravelQueryFilters\Examples\Http\Requests;

use CoderaWorks\LaravelQueryFilters\Http\Requests\Trait\HasQueryFiltersInRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExampleQueryFiltersRequest extends FormRequest
{
    use HasQueryFiltersInRequestTrait;

    public function rules(): array
    {
        return [
            'order_by' => ['string'],

            'order_by_direction' => ['string', Rule::in(['asc', 'desc'])],

            ...$this->getQueryFiltersRules(),
        ];
    }

    public function queryFiltersList(): string
    {
        return 'example-list';
    }

    public function orderBy(): string
    {
        return $this->validated('order_by'); // @phpstan-ignore-line
    }

    public function orderByDirection(): string
    {
        return $this->validated('order_by_direction'); // @phpstan-ignore-line
    }
}
