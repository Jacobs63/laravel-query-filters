<?php

use CoderaWorks\LaravelQueryFilters\Filter\PossibleQueryFiltersBag;
use CoderaWorks\LaravelQueryFilters\Filter\RequestProvidedPossibleQueryFilterInterface;
use CoderaWorks\LaravelQueryFilters\QueryFiltersProcessor;
use CoderaWorks\LaravelQueryFilters\Tests\TestClasses\Enum\QueryFiltersTestFiltersEnum;
use CoderaWorks\LaravelQueryFilters\Tests\TestClasses\Enum\QueryFiltersTestListingsEnum;
use CoderaWorks\LaravelQueryFilters\Tests\TestClasses\Filter\QueryFilterRegisteringTestQueryFilterInterface;
use CoderaWorks\LaravelQueryFilters\Tests\TestClasses\Filter\QueryFilterRegisteringTestQueryFilter2Interface;
use CoderaWorks\LaravelQueryFilters\Tests\TestClasses\Http\Controllers\QueryFiltersTestController;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Mockery\MockInterface;

test('Query filters are registered by the `QueryFiltersProcessor`', function () {
    /** @var QueryFiltersProcessor $processor */
    $processor = $this->app->make(QueryFiltersProcessor::class);

    $processor->registerFilters(
        QueryFiltersTestListingsEnum::TEST_LISTING->value,
        QueryFilterRegisteringTestQueryFilterInterface::class
    );

    $reflection = new \ReflectionClass($processor);

    $property = $reflection->getProperty('filters');
    $property->setAccessible(true);

    $registeredFilters = $property->getValue($processor);

    expect($registeredFilters)
        ->toBeArray()
        ->toHaveCount(1)
        ->toHaveKey(QueryFiltersTestListingsEnum::TEST_LISTING->value)
        ->and($registeredFilters[QueryFiltersTestListingsEnum::TEST_LISTING->value])
        ->toBeArray()
        ->toHaveCount(1)
        ->toContain(QueryFilterRegisteringTestQueryFilterInterface::class);
});

test('Filtering endpoint passes validated filters from request to the `QueryFiltersProcessor`', function () {
    $this->spy(
        QueryFiltersProcessor::class,
        static function (MockInterface $mock): void {
            $mock->shouldReceive('applyFilters')
                ->once()
                ->with(
                    QueryFiltersTestListingsEnum::TEST_LISTING->value,
                    Mockery::type(PossibleQueryFiltersBag::class),
                    Mockery::type(Builder::class),
                );
        }
    );

    Route::post(
        'filtering-endpoint',
        QueryFiltersTestController::class,
    );

    $response = $this->post(
        'filtering-endpoint',
        [
            'filters' => [
                [
                    'tag' => QueryFiltersTestFiltersEnum::TEST_FILTER->value,
                ],
            ]
        ],
    );

    $response->assertOk();
});

test('Only requested list is processed by the `QueryFiltersProcessor`', function () {
    $this->spy(
        QueryFilterRegisteringTestQueryFilterInterface::class,
        static function (MockInterface $mock): void {
            $mock->shouldReceive('valid')
                ->once()
                ->with(
                    QueryFiltersTestFiltersEnum::TEST_FILTER->value,
                    null,
                    null,
                )
                ->andReturn(true);

            $mock->shouldReceive('apply')
                ->once()
                ->with(
                    Mockery::type(Builder::class),
                    QueryFiltersTestFiltersEnum::TEST_FILTER->value,
                    null,
                    null,
                );

            $mock->shouldNotReceive('valid')
                ->with(
                    QueryFiltersTestFiltersEnum::TEST_FILTER_2->value,
                    null,
                    null,
                );

            $mock->shouldNotReceive('apply')
                ->with(
                    Mockery::type(Builder::class),
                    QueryFiltersTestFiltersEnum::TEST_FILTER_2->value,
                    null,
                    null,
                );
        }
    );

    $this->spy(
        QueryFilterRegisteringTestQueryFilter2Interface::class,
        static function (MockInterface $mock): void {
            $mock->shouldNotReceive(
                'valid',
                'apply',
            );
        }
    );

    /** @var QueryFiltersProcessor $processor */
    $processor = $this->app->make(QueryFiltersProcessor::class);

    $processor->registerFilters(
        QueryFiltersTestListingsEnum::TEST_LISTING->value,
        QueryFilterRegisteringTestQueryFilterInterface::class
    );
    $processor->registerFilters(
        QueryFiltersTestListingsEnum::TEST_LISTING_2->value,
        QueryFilterRegisteringTestQueryFilter2Interface::class
    );

    $bag = (new PossibleQueryFiltersBag)
        ->addFilter(
        new RequestProvidedPossibleQueryFilterInterface(
            tag: QueryFiltersTestFiltersEnum::TEST_FILTER->value,
            value: null,
            data: null,
        )
    );

    $processor->applyFilters(
        QueryFiltersTestListingsEnum::TEST_LISTING->value,
        $bag,
        DB::table('test'),
    );
});

test('Eloquent builder applies the `PossibleQueryFiltersBag` for the requested list to the query.', function () {
    $this->spy(
        QueryFilterRegisteringTestQueryFilterInterface::class,
        static function (MockInterface $mock): void {
            $mock->shouldReceive('valid')
                ->once()
                ->with(
                    QueryFiltersTestFiltersEnum::TEST_FILTER->value,
                    null,
                    null,
                )
                ->andReturn(true);

            $mock->shouldReceive('apply')
                ->once()
                ->with(
                    Mockery::type(Builder::class),
                    QueryFiltersTestFiltersEnum::TEST_FILTER->value,
                    null,
                    null,
                );
        }
    );

    /** @var QueryFiltersProcessor $processor */
    $processor = $this->app->make(QueryFiltersProcessor::class);

    $processor->registerFilters(
        QueryFiltersTestListingsEnum::TEST_LISTING->value,
        QueryFilterRegisteringTestQueryFilterInterface::class
    );

    $bag = (new PossibleQueryFiltersBag)
        ->addFilter(
            new RequestProvidedPossibleQueryFilterInterface(
                tag: QueryFiltersTestFiltersEnum::TEST_FILTER->value,
                value: null,
                data: null,
            )
        );

    $model = new class extends \Illuminate\Database\Eloquent\Model {
        protected $table = 'test';
    };

    $query = $model->newQuery();

    $query->filter(
        QueryFiltersTestListingsEnum::TEST_LISTING->value,
        $bag
    );
});

test('Base query builder applies the `PossibleQueryFiltersBag` for the requested list to the query.', function () {
    $this->spy(
        QueryFilterRegisteringTestQueryFilterInterface::class,
        static function (MockInterface $mock): void {
            $mock->shouldReceive('valid')
                ->once()
                ->with(
                    QueryFiltersTestFiltersEnum::TEST_FILTER->value,
                    null,
                    null,
                )
                ->andReturn(true);

            $mock->shouldReceive('apply')
                ->once()
                ->with(
                    Mockery::type(Builder::class),
                    QueryFiltersTestFiltersEnum::TEST_FILTER->value,
                    null,
                    null,
                );
        }
    );

    /** @var QueryFiltersProcessor $processor */
    $processor = $this->app->make(QueryFiltersProcessor::class);

    $processor->registerFilters(
        QueryFiltersTestListingsEnum::TEST_LISTING->value,
        QueryFilterRegisteringTestQueryFilterInterface::class
    );

    $bag = (new PossibleQueryFiltersBag)
        ->addFilter(
            new RequestProvidedPossibleQueryFilterInterface(
                tag: QueryFiltersTestFiltersEnum::TEST_FILTER->value,
                value: null,
                data: null,
            )
        );

    $query = DB::table('test');

    $query->filter(
        QueryFiltersTestListingsEnum::TEST_LISTING->value,
        $bag
    );
});
