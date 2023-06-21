# Laravel Query Filters

This package offers convenient class-defined filters for your eloquent queries.  
Each filter can be individually validated, allowing for complete control of your code base and avoiding exposing your
database structure to the outside world.  
The query filtering supports both the DB facade & the Eloquent query builder.

## Installation
Install the package via composer, using the terminal:
```bash
composer require coderaworks/laravel-query-filters
```

Additionally, you may publish the provided translations:
```bash
php artisan vendor:publish --tag=laravel-query-filters
```

## Usage
### Creating a filter
To create a filter, you simply create a class that implements the `\CoderaWorks\LaravelQueryFilters\Interfaces\QueryFilterInterface` interface:

```php
use \CoderaWorks\LaravelQueryFilters\Contract\QueryFilterInterface;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class ExampleFilter implements QueryFilterInterface
{
    public function pattern(): string
    {
        return 'example-filter';
    }

    public function valid(string $tag, mixed $value, mixed $data): bool
    {
        return true;
    }

    public function apply(Builder|EloquentBuilder $query, string $tag, mixed $value, mixed $data): void
    {
        $query->where('example-field', $value);
    }
}
```
The `pattern` returns a regex pattern that will be used to match the requested filters.  
The `valid` method returns whether the provided tag, value & data combination are correct for this filter.  
The `apply` method applies the filter to the query in any way you'd like.  

### Registering a filter
To register a filter, you simply register it the `QueryFiltersProcessor` in your ServiceProvider, e.g.:

```php
use CoderaWorks\LaravelQueryFilters\QueryFiltersProcessor;
use Illuminate\Support\ServiceProvider;

class ExampleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->afterResolving(
            QueryFiltersProcessor::class,
            function (QueryFiltersProcessor $queryFiltersProcessor) {
                $queryFiltersProcessor->registerFilters(
                    'listing-tag',
                    ExampleFilter::class,
                );
            }
        );
    }
}
```
Using the `afterResolving` with a callback also ensures the listing processor is instantiated lazily, which improves
the application's performance whenever you're not actually using the listing processor.

### Applying filters
To apply filters, you should first create a validated form request class, which implements
the `\CoderaWorks\LaravelQueryFilters\Http\Requests\Trait\HasQueryFiltersInRequestTrait` trait, e.g.:
```php
use CoderaWorks\LaravelQueryFilters\Http\Requests\Trait\HasQueryFiltersInRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class ExampleQueryFiltersRequest extends FormRequest
{
    use HasQueryFiltersInRequestTrait;

    public function rules(): array
    {
        return [
            // your custom additional validation rules go here
        ] + $this->getQueryFiltersRules();
    }

    public function queryFiltersList(): string
    {
        return 'example-list';
    }
}
```

The form request must also either implement the `queryFiltersList` method or the `$queryFiltersList` property, which returns the tag of the listing we're about to process.  
You should also make sure you return the listing rules provided from the trait using the method `getQueryFiltersRules`.

Then you could use the created & now validated request class in a controller:

```php
use CoderaWorks\LaravelQueryFilters\QueryFiltersProcessor;
use CoderaWorks\LaravelQueryFilters\Tests\TestClasses\Http\Requests\QueryFiltersTestRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ExampleQueryFiltersController
{
    public function __invoke(
        QueryFiltersExampleRequest $request,
    )
    {
        // Get a query instance which we want to filter, e.g. via `DB::table()`:
        $query = DB::table('your-table');
        
        $query->filter(
            $request->getQueryFiltersList(),
            $request->getPossibleQueryFiltersBag(),
        );

        // Get the query result, e.g. via `get()`:
        $result = $query->get();
    }
}
```

## Payload
You'll likely be pairing a Single Page Application such as Vue when using this package, so you'll want to implement a route for the list.
The payload should contain the query filters in the following shape:
```typescript
type Payload = {
    filters: [
        {
            tag: string
            value?: any
            data?: any
        }

    ]
}
```
Json payload example:
```json
{
  "filters": [
    {
      "tag": "example-filter",
      "value": "example-value",
      "data": {
        "key": "value"
      }
    },
    {
      "tag": "example-filter-2",
      "value": "example-value-2"
    },
    {
      "tag": "example-filter-3"
    }
  ]
}
```

## Examples
See the [examples](examples) folder for some additional code examples on how to use this package.
