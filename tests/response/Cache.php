<?php
namespace PHP_Spore\Test\Response;

use \PHP_Spore\Spore_Property;
class Cache
{
    public $url;
    /**
     * @Spore_Property(class = "PHP_Spore\Test\Response\Headers")
     */
    public $headers;
}

class Headers
{
    public $Host;
}