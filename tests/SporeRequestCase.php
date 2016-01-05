<?php
use \PHP_Spore\Spore_Request;

class SporeRequestTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->spore = Spore_Request::request("GET", "https://httpbin.org/", [
            "query" => [
                "key"  => "value"
            ]
        ]);
    }

    public function testQueryParams()
    {
        $params = $this->spore->getRequest()->getQuery()->toArray();
        $this->assertSame($params, [
            "key"  => "value"
        ]);
    }
}