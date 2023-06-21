<?php

use CoderaWorks\LaravelQueryFilters\Examples\Http\Controllers\ExampleQueryFiltersController;
use Illuminate\Support\Facades\Route;

Route::post(
    'filtering-endpoint',
    ExampleQueryFiltersController::class,
);
