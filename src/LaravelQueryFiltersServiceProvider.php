<?php

namespace CoderaWorks\LaravelQueryFilters;

use CoderaWorks\LaravelQueryFilters\Filter\PossibleQueryFiltersBag;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;

class LaravelQueryFiltersServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(QueryFiltersProcessor::class);

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'query-filters');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../publish/lang' => resource_path('lang/vendor/query-filters'),
            ], 'laravel-query-filters');
        }
    }

    public function boot(): void
    {
        Builder::macro('filter', function (string $list, ?PossibleQueryFiltersBag $bag) {
            /** @var Builder|EloquentBuilder $this */

            /** @var QueryFiltersProcessor $processor */
            $processor = resolve(QueryFiltersProcessor::class);

            $processor->applyFilters(
                $list,
                $bag,
                $this,
            );
        });
    }
}
