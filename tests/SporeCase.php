<?php
use Doctrine\Common\Annotations\AnnotationRegistry;

require_once(__DIR__ . '/response/HTTPBinResponse.php');
require_once(__DIR__ . '/response/HTTPBinResponseWithPerson.php');

class SporeTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->spore = \PHP_Spore\Spore::newFromArray([
            "base_url" => "https://httpbin.org",
            "methods" => [
                "get" => [
                    "path" => "/get",
                    "method" => "GET",
                    "model" => HTTPBinResponse::class
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
                    "method" => "POST",
                    "form-data" => [
                        "name"
                    ],
                    "model" => HTTPBinResponseWithPerson::class
                ]
            ]
        ]);

        AnnotationRegistry::registerFile('../src/Spore_Property.php');
    }

    public function testQueryRequest()
    {
        $result = $this->spore->query_params([
            "params" => [
                "limit" => 10
            ]
        ]);
        $this->assertEquals($result->limit, "10");
    }

    public function testPostRequest()
    {
        $result = $this->spore->post(
            [
                "form-data" => [
                    "name" => "Pata"
                ]
            ]
        );
        $this->assertEquals($result->url, "https://httpbin.org/post");
        $this->assertEquals("Pata", $result->json->name);
        $this->assertEquals(Person::class, get_class($result->json));
    }

    public function testGetRequest()
    {
        $result = $this->spore->get();
        $this->assertEquals($result->headers["Host"], "httpbin.org");
        $this->assertEquals($result->url, "https://httpbin.org/get");
    }
}