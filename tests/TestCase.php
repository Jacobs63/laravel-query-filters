<?php

namespace CoderaWorks\LaravelQueryFilters\Tests;

use CoderaWorks\LaravelQueryFilters\LaravelQueryFiltersServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelQueryFiltersServiceProvider::class,
        ];
    }
}
