<?php

abstract class Vehicle {
    public $brand;
    protected $mileage = 0;

    abstract public static function makeSound();
}

abstract class Animal {
    public $name;
    public $age;

    public abstract function birthday();
    public abstract function eat();
}

class Cat extends Animal {
    public function birthday() {
        $this->age++;
    }

    public function eat() {
        return "I like Whiskas";
    }
}

class Dog extends Animal {
    public function birthday() {
        $this->age++;
    }

    public function eat() {
        return "I like bones";
    }
}

class CatInBoots extends Cat {
    public function __construct($name, $age) {
        parent::__construct($name, $age);
        echo "Puss in Boots";
    }
}
