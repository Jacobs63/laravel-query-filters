<?php

namespace CoderaWorks\LaravelQueryFilters;

use CoderaWorks\LaravelQueryFilters\Exception\InvalidQueryFilterProvidedException;
use CoderaWorks\LaravelQueryFilters\Filter\PossibleQueryFiltersBag;
use CoderaWorks\LaravelQueryFilters\Interface\QueryFilterInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Traits\Conditionable;

class QueryFiltersProcessor
{
    use Conditionable;

    /**
     * @var array<string, array<int, class-string<QueryFilterInterface>>>
     */
    protected array $filters;

    public function __construct(
        protected readonly Container $app,
    )
    {
    }

    /**
     * @param string|string[] $lists
     * @param class-string<QueryFilterInterface>|class-string<QueryFilterInterface>[] $abstracts
     */
    public function registerFilters(string|array $lists, string|array $abstracts): static
    {
        $lists = is_array($lists) ? $lists : [$lists];
        $abstracts = is_array($abstracts) ? $abstracts : [$abstracts];

        foreach ($abstracts as $abstract) {
            if (!is_a($abstract, QueryFilterInterface::class, true)) {
                throw InvalidQueryFilterProvidedException::notImplementingInterface($abstract);
            }
        }

        foreach ($lists as $list) {
            if (!isset($this->filters[$list])) {
                $this->filters[$list] = [];
            }

            $this->filters[$list] = array_merge($this->filters[$list], $abstracts);
        }

        return $this;
    }

    public function applyFilters(
        string                   $list,
        ?PossibleQueryFiltersBag $possibleQueryFiltersBag,
        Builder|EloquentBuilder  $builder,
    ): void
    {
        if ($possibleQueryFiltersBag === null) {
            return;
        }

        if (!isset($this->filters[$list])) {
            return;
        }

        foreach ($possibleQueryFiltersBag->getPossibleQueryFilters() as $possibleQueryFilter) {
            foreach ($this->filters[$list] as $supportedFilter) {
                try {
                    /** @var QueryFilterInterface $filterInstance */
                    $filterInstance = $this->app->make($supportedFilter);

                    if (!preg_match("/{$filterInstance->pattern()}/", $possibleQueryFilter->tag())) {
                        continue;
                    }
                } catch (\Throwable $e) {
                    report($e);

                    continue;
                }

                if (!$filterInstance->valid(
                    tag: $possibleQueryFilter->tag(),
                    value: $possibleQueryFilter->value(),
                    data: $possibleQueryFilter->data()
                )) {
                    continue;
                }

                $filterInstance->apply(
                    query: $builder,
                    tag: $possibleQueryFilter->tag(),
                    value: $possibleQueryFilter->value(),
                    data: $possibleQueryFilter->data()
                );

                continue 2;
            }
        }
    }
}
