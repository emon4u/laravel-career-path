<?php

abstract class Vehicle {
    abstract public function getBaseFare(): int;
    abstract public function getPerKilo(): int;

    public function getTotal( $kilo ): int {
        return $this->getBaseFare() + ( $kilo * $this->getPerKilo() );
    }
}

interface HourlyRate {
    public function getHourlyRate(): int;

	public function getMinHour(): int;
}

class Car extends Vehicle implements HourlyRate {
    public function getBaseFare(): int {
        return 100;
    }

    public function getPerKilo(): int {
        return 10;
    }

    public function getHourlyRate(): int {
        return 50;
    }

	public function getMinHour(): int {
        return 5;
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

$newCar = new Car();
echo $newCar->getMinHour( 10 );