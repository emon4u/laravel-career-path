<?php
namespace BankingCLI;

use BankingApp\BankingApp;
use BankingApp\Classes\JsonStorage;

class RegisterAdmin
{
    private BankingApp $bankingApp;

    public function __construct()
    {
        $this->bankingApp = new BankingApp( new JsonStorage );
    }

    public function run(): void
    {
        $this->register();
    }

    private function register(): void
    {
        $name     = readline( "Enter admin Name: " );
        $email    = readline( "Enter admin Email: " );
        $password = readline( "Enter admin Password: " );

        $registerResult = $this->bankingApp->registerAdmin( $name, $email, $password );

        echo $registerResult['message'] . "\n";
    }
}
