<?php

namespace App\Enums;

enum TransactionType: string
{
    case DEBIT = 'debit';
    case CREDIT = 'credit';
    case TRANSFER = 'transfer';
    case REFUND = 'refund';
    case PAYMENT = 'payment';
    case WITHDRAWAL = 'withdrawal';
    case DEPOSIT = 'deposit';
}
