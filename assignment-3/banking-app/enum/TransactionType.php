<?php
namespace BankingApp\Enum;

enum TransactionType: string {
    case DEPOSIT    = 'deposit';
    case WITHDRAWAL = 'withdrawal';
    case TRANSFER   = 'transfer';
}
