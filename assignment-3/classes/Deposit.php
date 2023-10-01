<?php

namespace BankingApp\Classes;

use BankingApp\Enum\TransactionType;

class Deposit extends Transaction
{
    public function __construct( string $userEmail, float $amount, string $note )
    {
        parent::__construct( $userEmail, $amount, $note );

        $this->type = TransactionType::DEPOSIT;
    }
}
