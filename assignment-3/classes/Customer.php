<?php
namespace BankingApp\Classes;

use BankingApp\Enum\UserType;

class Customer extends User
{
    public function __construct( string $name, string $email, string $password )
    {
        parent::__construct( $name, $email, $password );
        $this->type = UserType::CUSTOMER;
    }
}
