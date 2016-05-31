<?php
require_once(__DIR__ . '/Person.php');
use PHP_Spore\Spore_Model;
use Doctrine\Common\Annotations\AnnotationRegistry;

class SporeHydratingTests extends PHPUnit_Framework_TestCase
{
    /**
     * @var $model Spore_Model
     */
    public $model;
    public $mockedPersonJson = [
        "name" => "Pato",
        "age" => 30,
        "partner" => [
            "name" => "Pata",
            "age" => 30
        ],
        "children" => [
            [
                "name" => "Patito",
                "age" => 10,
                "partner" => [
                    "name" => "Patita",
                    "age" => 10
                ]
            ],
            [
                "name" => "Patito feo",
                "age" => 8
            ]
        ],
        "contact_numbers" => [
            12,
            14,
            15,
            25,
            30
        ],
        "car" => [
            "model" => 89,
            "kms" => 10000,
            "name" => "corsa"
        ]
    ];

    public function setUp()
    {
        $this->model = new Spore_Model(Person::class);

        AnnotationRegistry::registerFile('../src/Spore_Property.php');
    }

    public function testMathWorks()
    {
        $this->assertEquals(2, 1 + 1);
    }

    public function testCanReadPropertiesFromAClass()
    {
        $this->assertContains("name", $this->model->getProperties());
        $this->assertContains("age", $this->model->getProperties());
        $this->assertContains("partner", $this->model->getProperties());
        $this->assertContains("children", $this->model->getProperties());
    }

    public function testCanHydratePersonFromArray()
    {
        $pata = $this->model->hydrateFrom($this->mockedPersonJson["partner"]);

        $this->assertEquals(30, $pata->age);
    }

    public function testHydrateWorksForNestedArray()
    {
        $pato = $this->model->hydrateFrom($this->mockedPersonJson);

        $this->assertEquals(89, $pato->car["model"]);
        $this->assertEquals("corsa", $pato->car["name"]);
    }

    public function testHydrateWorkForNestedObjects()
    {
        $pato = $this->model->hydrateFrom($this->mockedPersonJson);

        $this->assertEquals(30, $pato->partner->age);
    }

    public function testHydrateWorksForPrimitiveArrays()
    {
        $pato = $this->model->hydrateFrom($this->mockedPersonJson);

        $this->assertEquals(14, $pato->contact_numbers[1]);
    }

    public function testHydrateWorksForComplexArrays()
    {
        $pato = $this->model->hydrateFrom($this->mockedPersonJson);

        $this->assertEquals(8, $pato->children[1]->age);
    }
}
