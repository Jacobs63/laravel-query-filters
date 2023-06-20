<?php

namespace CoderaWorks\LaravelQueryFilters\Tests\TestClasses\Http\Requests;

use CoderaWorks\LaravelQueryFilters\Http\Requests\Trait\HasQueryFiltersInRequestTrait;
use CoderaWorks\LaravelQueryFilters\Tests\TestClasses\Enum\QueryFiltersTestListingsEnum;
use Illuminate\Foundation\Http\FormRequest;

class QueryFiltersTestRequest extends FormRequest
{
    use HasQueryFiltersInRequestTrait;

    public function rules(): array
    {
        return $this->getQueryFiltersRules();
    }

    public function queryFiltersList(): string
    {
        return QueryFiltersTestListingsEnum::TEST_LISTING->value;
    }
}
