<?php
namespace BankingApp\Classes;

use BankingApp\Enum\UserType;
use BankingApp\Interface\Model;

class User implements Model
{

    protected UserType $type;
    protected string $name;
    protected string $email;
    protected string $password;
    protected float $balance;
    protected array $userData;

    public function __construct( string $name, string $email, string $password )
    {
        $this->setName( $name );
        $this->setEmail( $email );
        $this->setPassword( $password );
    }

    public static function getModelName(): string
    {
        return 'users';
    }

    public function setName( string $name ): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setEmail( string $email ): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPassword( string $password ): void
    {
        $this->password = password_hash( $password, PASSWORD_BCRYPT );

    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUserData(): array
    {
        $this->userData['type']     = $this->type->value;
        $this->userData['name']     = $this->name;
        $this->userData['email']    = $this->email;
        $this->userData['password'] = $this->password;
        $this->userData['balance']  = 0.00;

        return $this->userData;
    }
}
