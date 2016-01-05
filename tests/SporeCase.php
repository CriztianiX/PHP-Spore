<?php
use \PHP_Spore as PHP_Spore;

class SporeTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->spore = \PHP_Spore\Spore::newFromArray([
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
    }

    public function testQueryRequest()
    {
        $result = $this->spore->query_params([
            "params" => [
                "limit" => 10
            ]
        ]);
        $this->assertEquals($result["limit"], "10");
    }

    public function testPostRequest()
    {
        $result = $this->spore->post();
        $this->assertEquals($result["url"], "https://httpbin.org/post");
    }

    public function testGetRequest()
    {
        $result = $this->spore->get();
        $this->assertEquals($result["url"], "https://httpbin.org/get");
    }
}