<?php
namespace BankingApp\Classes;

use BankingApp\Enum\UserType;

class Admin extends User
{
    public function __construct( string $name, string $email, string $password )
    {
        parent::__construct( $name, $email, $password );
        $this->type = UserType::ADMIN;
    }
}
