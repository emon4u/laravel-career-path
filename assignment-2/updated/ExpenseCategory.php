<?php

class ExpenseCategory extends Category
{
    public function __construct( string $name = "" )
    {
        $this->type = TransactionType::EXPENSE;
        $this->name = $name;
    }
}
