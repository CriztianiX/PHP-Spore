<?php
use \PHP_Spore\Spore_Property;

class Person
{
    public $name;
    public $age;
    /**
     * @Spore_Property(class = "Person")
     */
    public $partner;
    /**
     * @Spore_Property(class = "Person[]")
     */
    public $children;
    public $contact_numbers;
    public $car;
}