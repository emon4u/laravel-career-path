<?php
namespace BankingCLI;

use BankingApp\BankingApp;
use BankingApp\Classes\JsonStorage;
use BankingApp\Enum\UserType;

class CLIApp
{
    private BankingApp $bankingApp;
    private array $loggedInUser;

    public function __construct()
    {
        $this->bankingApp   = new BankingApp( new JsonStorage );
        $this->loggedInUser = [];
    }

    public function run(): void
    {
        while ( true ) {
            if ( empty( $this->loggedInUser ) ) {
                printf( "1. Login\n" );
                printf( "2. Register\n" );
                $choice = intval( readline( "Enter your option: " ) );

                switch ( $choice ) {
                    case 1:
                        $this->login();
                        break;
                    case 2:
                        $this->register();
                        break;
                    default:
                        printf( "Invalid option. Please try again.\n" );
                        break;
                }
            } else {
                if ( $this->loggedInUser['type'] === UserType::CUSTOMER->value ) {
                    printf( "1. Deposit Money\n" );
                    printf( "2. Withdraw Money\n" );
                    printf( "3. Transfer Money\n" );
                    printf( "4. View Current Balance\n" );
                    printf( "5. View Transactions\n" );
                } elseif ( $this->loggedInUser['type'] === UserType::ADMIN->value ) {
                    printf( "1. View All Customers\n" );
                    printf( "2. View All Transactions\n" );
                    printf( "3. View A User's Transactions\n" );
                }

                printf( "0. Logout\n" );

                $choice = intval( readline( "Enter your option: " ) );

                switch ( $choice ) {
                    case 0:
                        $this->logout();
                        break;
                    case 1:
                        if ( $this->loggedInUser['type'] === UserType::CUSTOMER->value ) {
                            $this->depositMoney();
                        } elseif ( $this->loggedInUser['type'] === UserType::ADMIN->value ) {
                            $this->viewAllCustomers();
                        }
                        break;
                    case 2:
                        if ( $this->loggedInUser['type'] === UserType::CUSTOMER->value ) {
                            $this->withdrawMoney();
                        } elseif ( $this->loggedInUser['type'] === UserType::ADMIN->value ) {
                            $this->viewAllTransactions();
                        }
                        break;
                    case 3:
                        if ( $this->loggedInUser['type'] === UserType::CUSTOMER->value ) {
                            $this->transferMoney();
                        } elseif ( $this->loggedInUser['type'] === UserType::ADMIN->value ) {
                            $this->viewAUserTransactions();
                        }
                        break;
                    case 4:
                        if ( $this->loggedInUser['type'] === UserType::CUSTOMER->value ) {
                            $this->viewCurrentBalance();
                        }
                        break;
                    case 5:
                        if ( $this->loggedInUser['type'] === UserType::CUSTOMER->value ) {
                            $this->viewTransactions();
                        }
                        break;
                    default:
                        printf( "Invalid option. Please try again.\n" );
                        break;
                }
            }
        }
    }

    private function login(): void
    {
        $email    = readline( "Enter your email: " );
        $password = readline( "Enter your password: " );

        $loginResult = $this->bankingApp->loginUser( $email, $password );

        if ( $loginResult['status'] === 'success' ) {
            printf( $loginResult['message'] . "\n" );
            $this->loggedInUser = $loginResult['user_data'];
        } else {
            printf( $loginResult['message'] . "\n" );
        }
    }

    private function register(): void
    {
        $name     = readline( "Enter your Name: " );
        $email    = readline( "Enter your Email: " );
        $password = readline( "Enter your Password: " );

        $registerResult = $this->bankingApp->registerCustomer( $name, $email, $password );

		printf( $registerResult['message'] . "\n" );
    }

    private function logout(): void
    {
        $this->loggedInUser = [];
        printf( "Logged out successfully.\n" );
    }

    private function depositMoney(): void
    {
        // Implement deposit money logic here
    }

    private function withdrawMoney(): void
    {
        // Implement withdraw money logic here
    }

    private function transferMoney(): void
    {
        // Implement transfer money logic here
    }

    private function viewCurrentBalance(): void
    {
        // Implement view current balance logic here
    }

    private function viewTransactions(): void
    {
        // Implement view transactions logic here
    }

    private function viewAllCustomers(): void
    {
        // Implement view all customers logic here
    }

    private function viewAllTransactions(): void
    {
        // Implement view all transactions logic here
    }

    private function viewAUserTransactions(): void
    {
        // Implement view a user's transactions logic here
    }
}
