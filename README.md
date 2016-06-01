# PHP-Spore
SPORE, a generic ReST client for PHP7
```php
require_once("vendor/autoload.php");
use PHP_Spore\Spore;
```
Now, you can generate a spore object from an array

```php
$spore = Spore::newFromArray([
   "base_url" => "https://httpbin.org",
   "methods" => [
       "get" => [
           "path" => "/get",
           "method" => "GET"
       ],
       "query_params" => [
           "path" => "/response-headers",
           "method" => "GET",
           "params" => [
               "limit" => [ "required" => true ]
           ]
       ],
       "post" => [
           "path" => "/post",
           "method" => "POST"
       ]
   ]
]);
```

or you can use a json file with the spec.
```php
$spore = Spore::newFromJson(__DIR__ . "spec.json");
```

Request and enpoint is very easy
```php
$response = $spore->post([
    "params" => [
        "limit" => 10,
        "page" => 1
    ],
    "form-data" => [
        "data" => [
            1,2,3
        ]
    ]
]);
```
