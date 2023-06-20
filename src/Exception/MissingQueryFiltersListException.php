<?php

namespace CoderaWorks\LaravelQueryFilters\Exception;

class MissingQueryFiltersListException extends \Exception
{
    public static function for(string $caller): self
    {
        return new self(
            sprintf(
                "The provided `%s` does not implement the method `%s` nor has a property `%s`!",
                "$caller::class",
                'queryFiltersList()',
                '$queryFiltersList',
            )
        );
    }
}
