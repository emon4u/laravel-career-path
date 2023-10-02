<?php
namespace BankingApp\Enum;

enum UserType: string {
    case ADMIN    = 'admin';
    case CUSTOMER = 'customer';
}
