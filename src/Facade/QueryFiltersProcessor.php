<?php

namespace CoderaWorks\LaravelQueryFilters\Facade;

use CoderaWorks\LaravelQueryFilters\QueryFiltersProcessor as ConcreteQueryFiltersProcessor;
use Illuminate\Support\Facades\Facade;

class QueryFiltersProcessor extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ConcreteQueryFiltersProcessor::class;
    }
}
