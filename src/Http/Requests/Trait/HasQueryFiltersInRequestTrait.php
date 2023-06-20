<?php

namespace CoderaWorks\LaravelQueryFilters\Http\Requests\Trait;

use CoderaWorks\LaravelQueryFilters\Exception\MissingQueryFiltersListException;
use CoderaWorks\LaravelQueryFilters\Filter\PossibleQueryFiltersBag;
use CoderaWorks\LaravelQueryFilters\Filter\RequestProvidedPossibleQueryFilterInterface;
use CoderaWorks\LaravelQueryFilters\Http\Rule\RequestProvidedFilterRule;

trait HasQueryFiltersInRequestTrait
{
    protected function getQueryFiltersRules(): array
    {
        return [
            'filters' => [
                'nullable',
                'array',
            ],
            'filters.*' => [
                new RequestProvidedFilterRule(),
            ]
        ];
    }

    /**
     * @throws MissingQueryFiltersListException
     */
    public function getQueryFiltersList(): string
    {
        $list = null;

        if (property_exists($this, 'queryFiltersList')) {
            $list = $this->queryFiltersList;
        };

        if (method_exists($this, 'queryFiltersList')) {
            $list = $this->queryFiltersList();
        };

        if (! is_string($list) || empty($list)) {
            throw MissingQueryFiltersListException::for(static::class);
        }

        return $list;
    }

    public function getPossibleQueryFiltersBag(): ?PossibleQueryFiltersBag
    {
        /** @var array|null $validatedFilters */
        $validatedFilters = $this->validated('filters');

        if ($validatedFilters === null) {
            return null;
        }

        $possibleQueryFiltersBag = new PossibleQueryFiltersBag;

        foreach ($validatedFilters as $validatedFilter) {
            $possibleQueryFiltersBag->addFilter(
                new RequestProvidedPossibleQueryFilterInterface(
                    tag: $validatedFilter['tag'],
                    value: $validatedFilter['value'] ?? null,
                    data: $validatedFilter['data'] ?? null,
                )
            );
        }

        return $possibleQueryFiltersBag;
    }
}
