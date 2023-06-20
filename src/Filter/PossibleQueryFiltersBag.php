<?php

namespace CoderaWorks\LaravelQueryFilters\Filter;

use CoderaWorks\LaravelQueryFilters\Interface\PossibleQueryFilterInterface;

class PossibleQueryFiltersBag
{
    protected array $possibleQueryFilters = [];

    public function addFilter(PossibleQueryFilterInterface $filter): static
    {
        $this->possibleQueryFilters[] = $filter;

        return $this;
    }

    /**
     * @return PossibleQueryFilterInterface[]
     */
    public function getPossibleQueryFilters(): array
    {
        return $this->possibleQueryFilters;
    }
}
