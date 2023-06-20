<?php

namespace CoderaWorks\LaravelQueryFilters\Exception;

class InvalidQueryFilterProvidedException extends \Exception
{
    public static function notImplementingInterface(string $abstract): self
    {
        return new self(
            "Provided query filter class {$abstract} does not implement PossibleQueryFilter interface!"
        );
    }
}
