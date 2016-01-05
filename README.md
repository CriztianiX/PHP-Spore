# PHP-Spore
SPORE, a generic ReST client for PHP7

# UNDER DEVELOPMENT
```php
require_once("vendor/autoload.php");
use PHP_Spore as PHP_Spore;

$spore = \PHP_Spore\Spore::newFromArray([
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
