<?php
require_once(__DIR__ . '/response/Cache.php');

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
                ],
                "getCache" => [
                    "path" => "/cache"
                ]
            ]
        ];

        $this->assertSame($expected, $spec);
    }

    public function testHydrateJson()
    {
        $json = __DIR__ . "/spec-model.json";
        $spore = \PHP_Spore\Spore::newFromJson($json);
        $cache = $spore->getCache();
        $this->assertSame($cache->url, "https://httpbin.org/cache");
    }

    public function testHydrateRelatedModelJson()
    {
        $json = __DIR__ . "/spec-model.json";
        $spore = \PHP_Spore\Spore::newFromJson($json);
        $cache = $spore->getCache();
        $this->assertEquals($cache->headers->Host, "httpbin.org");
    }
}