<?php

namespace CoderaWorks\LaravelQueryFilters\Interface;

interface PossibleQueryFilterInterface
{
    public function tag(): string;

    public function value(): mixed;

    public function data(): mixed;
}
