<?php

class BankAccount {
    // Properties
    public $accountNumber;
    public $balance;

    // methods
    public function deposit( $amount ) {
        if ( $amount > 0 ) {
            $this->balance += $amount;
        }

        return $this;
    }

    public function withdraw( $amount ) {
        if ( $amount <= $this->balance ) {
            $this->balance -= $amount;
            return true;
        }
        return false;
    }
}

$accountOne                = New BankAccount();
$accountOne->accountNumber = '1';
$accountOne->balance       = 2000;
// $accountOne->deposit( 5000 );
// $accountOne->withdraw( 2000 );

// Method chaining.
$accountOne->deposit( 2000 )->withdraw( 1000 );

// var_dump( $accountOne->balance );

class Customer {
    private string $name;

    function __construct( $name ) {
        $this->setName( $name );
    }

    public function setName( string $name ): string {
        $name = trim( $name );

        if ( $name == '' ) {
            return false;
        }

        $this->name = $name;

        return true;
    }

    public function getName(): string {
        return $this->name;
    }

    function __destruct() {
    }
}

// $emon = new Customer();
// $emon->setName( 'Emon Ahmed' );
// echo $emon->getName();

$emon = new Customer( 'Emon Ahmed' );
$emon->setName( 'Ahmed Emon' );

echo $emon->getName();