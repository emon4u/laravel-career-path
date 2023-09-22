<?php

abstract class Vehicle {
    abstract public function getBaseFare(): int;
    abstract public function getPerKilo(): int;

    public function getTotal( $kilo ): int {
        return $this->getBaseFare() + ( $kilo * $this->getPerKilo() );
    }
}

class Car extends Vehicle {
    public function getBaseFare(): int {
        return 100;
    }

    public function getPerKilo(): int {
        return 10;
    }
}

class Bike extends Vehicle {
    public function getBaseFare(): int {
        return 80;
    }

    public function getPerKilo(): int {
        return 5;
    }
}

// $newCar = new Car();
// echo $newCar->getTotal( 10 );

// $newBike = new Bike();
// echo $newCar->getTotal( 10 );

abstract class Animal {
    protected $petName;

    public function __construct( $petName ) {
        $this->petName = $petName;
    }

    abstract public function name(): string;
    abstract public function scientificName(): string;

    public function getInfo() {
        echo "Name: {$this->name()} \n";
        echo "Scientific Name: {$this->scientificName()} \n";
        echo "Pet Name: {$this->petName} \n";
    }
}

class Cat extends Animal {
    public function __construct($petName) {
		parent::__construct( $petName );
		echo "Cat Info: \n";
    }

    public function name(): string {
        return 'Cat';
    }

    public function scientificName(): string {
        return 'Felis catus';
    }
}

class Mouse extends Animal {
    public function name(): string {
        return 'Mouse';
    }

    public function scientificName(): string {
        return 'Mus musculus';
    }
}

$newCat = new Cat( 'Tom' );
$newCat->getInfo();
$newMouse = new Mouse( 'Jerry' );
$newMouse->getInfo();