<?php

namespace CoderaWorks\LaravelQueryFilters\Examples;

use CoderaWorks\LaravelqueryFilters\Examples\Filter\ExampleFallbackFilter;
use CoderaWorks\LaravelqueryFilters\Examples\Filter\ExampleFilter;
use CoderaWorks\LaravelqueryFilters\Examples\Filter\ExampleFilterWithOperator;
use CoderaWorks\LaravelQueryFilters\QueryFiltersProcessor;
use Illuminate\Support\ServiceProvider;

class ExampleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->afterResolving(
            QueryFiltersProcessor::class,
            function (QueryFiltersProcessor $queryFiltersProcessor) {
                $queryFiltersProcessor->registerFilters(
                    'example-list',
                    [
                        ExampleFilter::class,
                        ExampleFilterWithOperator::class,
                        ExampleFallbackFilter::class,
                    ],
                );
            }
        );
    }
}
