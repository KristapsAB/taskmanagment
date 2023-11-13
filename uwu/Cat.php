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

class CatClass {
    public $name;
    private $age;

    public function Birthday() {
        $this->age++;
        echo "-" . $this->age . "<br>";
    }

    public static function meow() {
        echo "Meow!";
    }
}

$Mincis = new CatClass();
$Brincis = new CatClass();

$Mincis->name = "Mincis";
$Brincis->name = "Brincis";
$Mincis->age = 0;
$Brincis->age = 0;

CatClass::meow();

class DogClass {
    public $name;
    private $age;

    public function __construct($name, $age) {
        $this->name = $name;
        $this->age = $age;
    }

    public function Birthday() {
        $this->age++;
    }

    public static function woof() {
        echo "Woof!";
    }
}

$Reksis = new DogClass("Reksis", 6);

$Reksis->Birthday();
DogClass::woof();
?>
