<?php
use \PHP_Spore\Spore_Property;

require_once __DIR__ . '/Person.php';

class HTTPBinResponseWithPerson
{
    public $headers;
    public $origin;
    public $url;
    /**
     * @Spore_Property(class = "Person")
     */
    public $json;
}