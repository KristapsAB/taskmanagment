<?php
require_once("Car.php");

$myCar = new Car();
$myCar->brand = "Dacia";

echo $myCar->brand <br>;

$myCar = new Car("Dacia", 520);

Car::makeNoise();
