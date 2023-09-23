<?php

class EXPENSE extends Transaction
{
    public function __construct()
    {
        $this->type = TransactionType::EXPENSE;
    }
}
