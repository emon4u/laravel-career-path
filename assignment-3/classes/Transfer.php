<?php

namespace BankingApp\Classes;

use BankingApp\Enum\TransactionType;

class Transfer extends Transaction
{
    public function __construct( string $senderEmail, float $amount, string $recipientEmail, string $note )
    {
        parent::__construct( $senderEmail, $amount, $note );

        $this->type = TransactionType::TRANSFER;

        $this->setRecipientEmail( $recipientEmail );
    }
}
