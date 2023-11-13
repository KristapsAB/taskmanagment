<?php
require_once("Vehicle.php");

class Car extends Vehicle{
    
    
}

class Car{
    public $brand
}
public $brand;
private $mileage = 0;

function __construct($b, $m){
    $this->brand = $b;
    $this->mileage = $m;
}
function __destruct(){
    echo "The car {$this->brand} was destroyed";
}

public function makeNoise(){
    echo "beep beep!!!!!!";
}

