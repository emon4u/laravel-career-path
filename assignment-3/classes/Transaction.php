<?php

namespace BankingApp\Classes;

use BankingApp\Enum\TransactionType;
use BankingApp\Interface\Model;

class Transaction implements Model
{
    protected TransactionType $type;
    protected string $userEmail;
    protected float $amount;
    protected ?string $recipientEmail;
    protected string $timestamp;
    protected string $note;
    protected $transactionData;

    public function __construct( string $userEmail, float $amount, string $note )
    {
        $this->setUserEmail( $userEmail );
        $this->setAmount( $amount );
        $this->setRecipientEmail( null );
        $this->setTimestamp();
        $this->setNote( $note );
    }

    public static function getModelName(): string
    {
        return 'transactions';
    }

    public function setUserEmail( string $email ): void
    {
        $this->userEmail = $email;
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function setAmount( float $amount ): void
    {
        $this->amount = $amount;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setRecipientEmail( ?string $email ): void
    {
        $this->recipientEmail = $email;
    }

    public function getRecipientEmail(): string
    {
        return $this->recipientEmail;
    }

    public function setTimestamp(): string
    {
        return $this->timestamp = date( "Y-m-d H:i:s" );
    }

    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    public function setNote( string $note ): void
    {
        $this->note = $note;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function getTransactionData(): array
    {
        $this->transactionData['type']            = $this->type->value;
        $this->transactionData['user_email']    = $this->userEmail;
        $this->transactionData['amount']          = $this->amount;
        $this->transactionData['recipient_email'] = $this->recipientEmail;
        $this->transactionData['timestamp']       = $this->timestamp;
        $this->transactionData['note']            = $this->note;

        return $this->transactionData;
    }
}
