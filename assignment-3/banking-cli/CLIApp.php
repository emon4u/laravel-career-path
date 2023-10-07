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
                printf( "3. Exit\n" );
                $choice = intval( readline( "Enter your option: " ) );

                switch ( $choice ) {
                    case 1:
                        $this->login();
                        break;
                    case 2:
                        $this->register();
                        break;
                    case 3:
                        printf( "Exit Application.\n" );
                        exit( 0 );
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
                    printf( "6. Logout\n" );
                } elseif ( $this->loggedInUser['type'] === UserType::ADMIN->value ) {
                    printf( "1. View All Customers\n" );
                    printf( "2. View All Transactions\n" );
                    printf( "3. View A User's Transactions\n" );
                    printf( "4. Logout\n" );
                }

                $choice = intval( readline( "Enter your option: " ) );

                switch ( $choice ) {
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
                        } elseif ( $this->loggedInUser['type'] === UserType::ADMIN->value ) {
                            $this->logout();
                        }
                        break;
                    case 5:
                        if ( $this->loggedInUser['type'] === UserType::CUSTOMER->value ) {
                            $this->viewTransactions();
                        }
                        break;
                    case 6:
                        $this->logout();
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
            echo $loginResult['message'] . "\n";
            $this->loggedInUser = $loginResult['user_data'];
        } else {
            echo $loginResult['message'] . "\n";
        }
    }

    private function register(): void
    {
        $name     = readline( "Enter your Name: " );
        $email    = readline( "Enter your Email: " );
        $password = readline( "Enter your Password: " );

        $registerResult = $this->bankingApp->registerCustomer( $name, $email, $password );

        echo $registerResult['message'] . "\n";
    }

    private function logout(): void
    {
        $this->loggedInUser = [];
        printf( "Logged out successfully.\n" );
    }

    private function depositMoney(): void
    {
        $amount = readline( "Enter your amount: " );
        $note   = readline( "Add a deposit note: " );

        $depositResult = $this->bankingApp->addDeposit( $this->loggedInUser['email'], $amount, $note );

        echo $depositResult['message'] . "\n";
    }

    private function withdrawMoney(): void
    {
        $amount = readline( "Enter your amount: " );
        $note   = readline( "Add a deposit note: " );

        $withdrawalResult = $this->bankingApp->addWithdrawal( $this->loggedInUser['email'], $amount, $note );

        echo $withdrawalResult['message'] . "\n";
    }

    private function transferMoney(): void
    {
        $amount         = readline( "Enter your amount: " );
        $recipientEmail = readline( "Enter recipient email: " );
        $note           = readline( "Add a deposit note: " );

        $transferResult = $this->bankingApp->addTransfer( $this->loggedInUser['email'], $amount, $recipientEmail, $note );

        echo $transferResult['message'] . "\n";
    }

    private function viewCurrentBalance(): void
    {
        $userBalance = $this->bankingApp->getUserBalance( $this->loggedInUser['email'] );

        if ( is_null( $userBalance ) ) {
            printf( "Something went wrong! \n" );
        }

        printf( "Your Current Balance is %d\n", $userBalance );
    }

    private function viewTransactions(): void
    {
        $transactions = $this->bankingApp->getTransactionsByUser( $this->loggedInUser['email'] );

        printf( "Transactions by %s\n", $this->loggedInUser['name'] );
        printf( "----------------------\n" );
        foreach ( $transactions as $transaction ) {
            printf( "Transition type: %s \n", ucfirst( $transaction['type'] ) );
            printf( "Amount: %d\n", $transaction['amount'] );
            printf( "Time: %s\n", $transaction['timestamp'] );
            printf( "Note: %s\n", $transaction['note'] );

            if ( $transaction['type'] === 'transfer' ) {
                printf( "Recipient Email: %s\n", $transaction['recipient_email'] );
            }
            printf( "----------------------\n" );
        }
    }

    private function viewAllCustomers(): void
    {
        $customers = $this->bankingApp->getAllUsers();

        printf( "List of all customers\n" );
        printf( "----------------------\n" );
        foreach ( $customers as $customer ) {
            if ( $customer['type'] === 'customer' ) {
                printf( "Name: %s\n", $customer['name'] );
                printf( "Email: %s\n", $customer['email'] );
                printf( "Balance: %d\n", $customer['balance'] );
                printf( "----------------------\n" );
            }
        }
    }

    private function viewAllTransactions(): void
    {
        $transactions = $this->bankingApp->getAllTransactions();

        printf( "Transactions by all customers\n" );
        printf( "----------------------\n" );
        foreach ( $transactions as $transaction ) {
            printf( "User Email: %s\n", $transaction['user_email'] );
            printf( "Transition type: %s \n", ucfirst( $transaction['type'] ) );
            printf( "Amount: %d\n", $transaction['amount'] );
            printf( "Time: %s\n", $transaction['timestamp'] );
            printf( "Note: %s\n", $transaction['note'] );

            if ( $transaction['type'] === 'transfer' ) {
                printf( "Recipient Email: %s\n", $transaction['recipient_email'] );
            }
            printf( "----------------------\n" );
        }

    }

    private function viewAUserTransactions(): void
    {

        $userEmail = readline( "Enter customers email: " );

        $transactions = $this->bankingApp->getTransactionsByUser( $userEmail );

        if (  ! $transactions ) {
            printf( "User not found! \n" );

            return;
        }

        printf( "Transactions by %s\n", $userEmail );
        printf( "----------------------\n" );
        foreach ( $transactions as $transaction ) {
            printf( "Transition type: %s \n", ucfirst( $transaction['type'] ) );
            printf( "Amount: %d\n", $transaction['amount'] );
            printf( "Time: %s\n", $transaction['timestamp'] );
            printf( "Note: %s\n", $transaction['note'] );

            if ( $transaction['type'] === 'transfer' ) {
                printf( "Recipient Email: %s\n", $transaction['recipient_email'] );
            }
            printf( "----------------------\n" );
        }
    }
}
