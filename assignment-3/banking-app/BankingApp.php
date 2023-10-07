<?php
namespace BankingApp;

use BankingApp\Classes\Admin;
use BankingApp\Classes\Customer;
use BankingApp\Classes\Deposit;
use BankingApp\Classes\Transaction;
use BankingApp\Classes\Transfer;
use BankingApp\Classes\User;
use BankingApp\Classes\Withdrawal;
use BankingApp\Enum\UserType;
use BankingApp\Interface\Storage;

class BankingApp
{
    private array $transactions;
    private array $users;
    private Storage $storage;

    public function __construct( Storage $storage )
    {
        $this->storage = $storage;

        $this->users        = $this->storage->load( User::getModelName() );
        $this->transactions = $this->storage->load( Transaction::getModelName() );
    }

    public function loginUser( string $email, string $pass )
    {
        $returnData = [
            'status'    => 'failed',
            'message'   => "Sorry the given credentials didn't match!",
            'user_data' => [],
        ];

        foreach ( $this->users as $user ) {
            if ( $user['email'] === $email ) {
                if ( password_verify( $pass, $user['password'] ) ) {
                    $returnData['status']    = 'success';
                    $returnData['message']   = "Welcome back " . $user['name'];
                    $returnData['user_data'] = $this->getLoggedUserData( $email );

                    return $returnData;
                }
            }
        }

        return $returnData;
    }

    public function registerCustomer( string $name, string $email, string $pass ): array
    {
        $returnData = [
            'status'  => 'failed',
            'message' => 'Something went wrong!',
        ];

        if ( empty( $name ) || empty( $email ) || empty( $pass ) ) {
            $returnData['message'] = 'Check all required parameter.';
            return $returnData;
        }

        foreach ( $this->users as $user ) {
            if ( $user['email'] === $email ) {
                $returnData['message'] = "User with the email $email already exists.";
                return $returnData;
            }
        }

        $customer      = new Customer( $name, $email, $pass );
        $this->users[] = $customer->getUserData();

        $saved = $this->saveUsers();

        if ( $saved ) {
            $returnData['status']  = 'success';
            $returnData['message'] = 'Customer has successfully registered.';
        }

        return $returnData;
    }

    public function registerAdmin( string $name, string $email, string $pass ): array
    {
        $returnData = [
            'status'  => 'failed',
            'message' => 'Something went wrong!',
        ];

        if ( empty( $name ) || empty( $email ) || empty( $pass ) ) {
            $returnData['message'] = 'Check all required parameter.';
            return $returnData;
        }

        foreach ( $this->users as $user ) {
            if ( $user['email'] === $email ) {
                $returnData['message'] = "User with the email $email already exists.";
                return $returnData;
            }
        }

        $admin         = new Admin( $name, $email, $pass );
        $this->users[] = $admin->getUserData();

        $saved = $this->saveUsers();

        if ( $saved ) {
            $returnData['status']  = 'success';
            $returnData['message'] = 'Admin has successfully registered.';
        }

        return $returnData;
    }

    public function addDeposit( string $userEmail, float $amount, string $note ): array
    {
        $returnData = [
            'status'  => 'failed',
            'message' => 'Transaction failed!',
        ];

        if ( empty( $userEmail ) || empty( $amount ) || empty( $note ) ) {
            $returnData['message'] = 'Check all required parameter';

            return $returnData;
        }

        $userToUpdate = null;

        foreach ( $this->users as &$user ) {
            if ( $userEmail === $user['email'] ) {
                $userToUpdate = &$user;
                break;
            }
        }

        if ( $userToUpdate !== null ) {
            $userToUpdate['balance'] += $amount;

            $deposit              = new Deposit( $userEmail, $amount, $note );
            $this->transactions[] = $deposit->getTransactionData();

            $saved = $this->saveTransactions();

            if ( $saved ) {
                $returnData['status']  = 'success';
                $returnData['message'] = 'Transaction successfully complete.';
            }
        }

        return $returnData;
    }

    public function addWithdrawal( string $userEmail, float $amount, string $note ): array
    {
        $returnData = [
            'status'  => 'failed',
            'message' => 'Transaction failed!',
        ];

        if ( empty( $userEmail ) || empty( $amount ) || empty( $note ) ) {
            $returnData['message'] = 'Check all required parameter.';

            return $returnData;
        }

        $userToUpdate = null;

        foreach ( $this->users as &$user ) {
            if ( $userEmail === $user['email'] ) {
                $userToUpdate = &$user;
                break;
            }
        }

        if ( $userToUpdate !== null ) {
            if ( $amount >= $userToUpdate['balance'] ) {
                $returnData['message'] = 'Insufficient balance.';
                return $returnData;
            }

            $userToUpdate['balance'] -= $amount;

            $withdrawal           = new Withdrawal( $userEmail, $amount, $note );
            $this->transactions[] = $withdrawal->getTransactionData();

            $saved = $this->saveTransactions();

            if ( $saved ) {
                $returnData['status']  = 'success';
                $returnData['message'] = 'Transaction successfully complete.';
            }
        }

        return $returnData;
    }

    public function addTransfer( string $senderEmail, float $amount, string $recipientEmail, string $note ): array
    {
        $returnData = [
            'status'  => 'failed',
            'message' => 'Transaction failed!',
        ];

        if ( empty( $senderEmail ) || empty( $amount ) || empty( $recipientEmail ) || empty( $note ) ) {
            $returnData['message'] = 'Check all required parameter';

            return $returnData;
        }

        $senderToUpdate    = null;
        $recipientToUpdate = null;

        foreach ( $this->users as &$user ) {
            if ( $senderEmail === $user['email'] ) {
                $senderToUpdate = &$user;
            } elseif ( $recipientEmail === $user['email'] ) {
                $recipientToUpdate = &$user;
            }

            if ( $senderToUpdate !== null && $recipientToUpdate !== null ) {
                break;
            }
        }

        if ( $senderToUpdate !== null && $recipientToUpdate !== null ) {
            if ( $senderToUpdate['type'] === UserType::CUSTOMER->value && $recipientToUpdate['type'] === UserType::ADMIN->value ) {
                $returnData['message'] = 'Customer cannot transfer money to an admin account.';
                return $returnData;
            }

            if ( $amount >= $senderToUpdate['balance'] ) {
                $returnData['message'] = 'Insufficient balance for the transfer.';
                return $returnData;
            }

            $senderToUpdate['balance'] -= $amount;
            $recipientToUpdate['balance'] += $amount;

            $transfer             = new Transfer( $senderEmail, $amount, $recipientEmail, $note );
            $this->transactions[] = $transfer->getTransactionData();

            $deposit              = new Deposit( $recipientEmail, $amount, $note );
            $this->transactions[] = $deposit->getTransactionData();

            $saved = $this->saveTransactions();

            if ( $saved ) {
                $returnData['status']  = 'success';
                $returnData['message'] = 'Transaction successfully.';
            }
        } else {
            $returnData['message'] = 'Sender or recipient not found for the transaction.';
        }

        return $returnData;
    }

    public function getTransactionsByUser( string $userEmail ): array
    {
        $userTransactions = [];

        foreach ( $this->transactions as $transaction ) {
            if ( $transaction['user_email'] === $userEmail ) {
                $userTransactions[] = $transaction;
            }
        }

        return $userTransactions;
    }

    public function getUserBalance( string $userEmail ): ?float
    {
        foreach ( $this->users as $user ) {
            if ( $user['email'] === $userEmail ) {
                return $user['balance'];
            }
        }

        return null; // User not found
    }

    public function getAllTransactions(): array
    {
        return $this->transactions;
    }

    public function getAllUsers(): array
    {
        return $this->users;
    }

    private function getLoggedUserData( string $userEmail ): array
    {
        $userData = [];

        foreach ( $this->users as $user ) {
            if ( $user['email'] === $userEmail ) {
                $userData['type']  = $user['type'];
                $userData['name']  = $user['name'];
                $userData['email'] = $user['email'];
                break;
            }
        }

        return $userData;
    }

    private function saveTransactions(): bool
    {
        $transactionSaved = $this->storage->save( Transaction::getModelName(), $this->transactions );
        $updateBalance    = $this->storage->save( User::getModelName(), $this->users );

        if ( $updateBalance && $transactionSaved ) {
            return true;
        }

        return false;
    }

    private function saveUsers(): bool
    {
        $updateBalance = $this->storage->save( User::getModelName(), $this->users );

        if ( $updateBalance ) {
            return true;
        }

        return false;
    }
}
