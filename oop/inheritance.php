<?php
// class BankAccount {
//     private $balance;

//     public function __construct( $balance ) {
//         $this->balance = $balance;
//     }

//     public function getBalance() {
//         return $this->balance;
//     }

//     public function deposit( $amount ) {
//         if ( $amount > 0 ) {
//             $this->balance += $amount;
//         }

//         return $this;
//     }
// }

// class SavingAccount extends BankAccount {
//     private $interestRate;

//     public function __construct( $balance, $interestRate ) {
//         parent::__construct( $balance );
//         $this->interestRate = $interestRate;

//     }

//     // public function setInterestRate( $interestRate ) {
//     //     $this->interestRate = $interestRate;
//     // }

//     public function addInterest() {
//         // calculate interest
//         $interest = $this->interestRate * $this->getBalance();
//         // deposit interest to the balance
//         $this->deposit( $interest );
//     }
// }

// $account = new SavingAccount( 100, 0.05 );
// $account->deposit( 100 );

// $account->addInterest();

// echo $account->getBalance();

class BankAccount {
    private string $accountName;
    private int $accountNumber;
    private int $balance;

    function __construct( string $accountName, int $accountNumber ) {
        $this->setAccountName( $accountName );
        $this->setAccountNumber( $accountNumber );
        $this->balance = 0;
    }

    public function setAccountName( $accountName ) {
        return $this->accountName = $accountName;
    }

    public function setAccountNumber( $accountNumber ) {
        return $this->accountNumber = $accountNumber;
    }

    public function getAccountName(): string {
        return $this->accountName;
    }

    public function getAccountNumber(): int {
        return $this->accountNumber;
    }

    public function getBalance(): int {
        return $this->balance;
    }

    public function deposit( int $amount ) {
        if ( $amount > 0 ) {
            $this->balance += $amount;
        }
        return $this;
    }

    public function withdraw( $amount ) {
        if ( $amount > 0 && $amount <= $this->balance ) {
            $this->balance -= $amount;
            return true;
        }
        return false;
    }
}

class SavingAccount extends BankAccount {
    private $interestRate;

    public function __construct( string $accountName, int $accountNumber, $interestRate ) {
        parent::__construct( $accountName, $accountNumber );
        $this->interestRate = $interestRate / 100;

    }

    // public function setInterestRate( $interestRate ) {
    //     $this->interestRate = $interestRate / 100;
    // }

    public function addInterest() {
        // calculate interest
        $interest = $this->interestRate * $this->getBalance();
        // deposit interest to the balance
        $this->deposit( $interest );
    }
}

// $accountOne = new BankAccount( 'Emon Ahmed', '012' );
// $accountOne->setAccountName( 'Limon Ahmed' );
// echo $accountOne->getAccountName();
// echo $accountOne->getAccountNumber();

// $accountOne = new SavingAccount( 'Emon Ahmed', '012', 50 );
// $accountOne->deposit( 100 );
// $accountOne->setInterestRate( 5 );
// $accountOne->addInterest();

// echo $accountOne->getBalance();
// $accountOne->withdraw(60);
// echo "\n";
// echo $accountOne->getBalance();

class ParentClass {
	protected function helloParent() {
        echo "Hello Parent\n";
    }

    protected function sayHi() {
        echo "Hi from Parent Class\n";
    }

    public final function sayHello() {
        echo "Hello";
    }
}

class ChildClass extends ParentClass {
    public function sayHi() {
        // parent::sayHi();
        parent::helloParent();
        echo "Hi from Child Class";
    }

    // public function sayHello() {
    //     echo "Hello From Child Class";
    // }
}

$obj = new ChildClass();
$obj->sayHi();