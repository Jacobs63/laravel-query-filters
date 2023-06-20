<?php

namespace CoderaWorks\LaravelQueryFilters\Examples\Http\Controllers;

use CoderaWorks\LaravelQueryFilters\Examples\Http\Requests\ExampleQueryFiltersRequest;
use CoderaWorks\LaravelQueryFilters\QueryFiltersProcessor;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ExampleQueryFiltersController
{
    public function __invoke(
        ExampleQueryFiltersRequest $request,
    ): JsonResponse
    {
        $query = DB::table('example');

        $query->filter(
            $request->getQueryFiltersList(),
            $request->getPossibleQueryFiltersBag(),
        );

        $query->orderBy(
            $request->orderBy(),
            $request->orderByDirection(),
        );

        // Get the query result, e.g. via `pluck()`:
        $results = $query->pluck('id');

        return response()
            ->json(
                data: [
                    'ids' => $results,
                ],
            );
    }
}
