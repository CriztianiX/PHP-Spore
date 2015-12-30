# PHP-Spore
SPORE, a generic ReST client for PHP7

```php
require_once("vendor/autoload.php");
use PHP_Spore as PHP_Spore;

$spore = \PHP_Spore\Spore::newFromArray([
    "base_url" => "http://192.168.0.241:3434/api/v1",
    "methods" => [
        "users" => [
            "path" => "/categories",
            "method" => "GET"
        ],
        "discounts_filter" => [
            "path" => "/discounts",
            "method" => "POST",
            "form-data" => [
                "categories"
            ],
            "params" => [
                "limit" => [ "required" => true ],
                "page"
            ]
        ]
    ]
]);
$response = $spore->discounts_filter([
    "params" => [
        "limit" => 1,
        "page" => 1
    ],
    "form-data" => [
        "categories" => [
            1
        ]
    ]
]);
```
