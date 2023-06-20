<?php

namespace CoderaWorks\LaravelQueryFilters\Tests\TestClasses\Http\Controllers;

use CoderaWorks\LaravelQueryFilters\QueryFiltersProcessor;
use CoderaWorks\LaravelQueryFilters\Tests\TestClasses\Http\Requests\QueryFiltersTestRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class QueryFiltersTestController
{
    public function __invoke(
        QueryFiltersTestRequest $request,
        QueryFiltersProcessor   $queryFiltersProcessor,
    ): JsonResponse
    {
        $queryFiltersProcessor->applyFilters(
            $request->getQueryFiltersList(),
            $request->getPossibleQueryFiltersBag(),
            DB::table('__test__'),
        );

        return response()
            ->json(status: SymfonyResponse::HTTP_OK);
    }
}
