<?php

namespace CoderaWorks\LaravelQueryFilters\Http\Rule;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RequestProvidedFilterRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->valid($value)) {
            $fail(__('laravel-query-filters::validation.invalid_filter_provided'));
        }
    }

    protected function valid(mixed $value): bool
    {
        if (! is_array($value)) {
            return false;
        }

        if (!isset($value['tag'])) {
            return false;
        }

        if (isset($value['data']) && !is_array($value['data'])) {
            return false;
        }

        return true;
    }
}
