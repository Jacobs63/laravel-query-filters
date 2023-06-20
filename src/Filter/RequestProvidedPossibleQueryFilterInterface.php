<?php

namespace CoderaWorks\LaravelQueryFilters\Filter;

use CoderaWorks\LaravelQueryFilters\Interface\PossibleQueryFilterInterface;

class RequestProvidedPossibleQueryFilterInterface implements PossibleQueryFilterInterface
{
    public function __construct(
        protected readonly string $tag,
        protected readonly mixed $value,
        protected readonly mixed $data,
    )
    {}

    public function tag(): string
    {
        return $this->tag;
    }

    public function value(): mixed
    {
        return $this->value;
    }

    public function data(): mixed
    {
        return $this->data;
    }
}
