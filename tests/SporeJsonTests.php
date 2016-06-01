<?php

class SporeJsonTests extends PHPUnit_Framework_TestCase
{

    public function testLoadingJson()
    {
        $json = __DIR__ . "/spec.json";
        $spore = \PHP_Spore\Spore::newFromJson($json);
        $spec = $spore->spec();
        $expected = [
            "base_url" => "https://httpbin.org",
            "methods" => [
                "getMyIp" => [
                    "path" => "/ip"
                ]
            ]
        ];

        $this->assertSame($expected, $spec);
    }
}