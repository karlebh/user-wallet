<?php

namespace App\Actions;

use App\Models\Wallet;

class CurrencyToCryptoAction
{
    public function exceute()
    {
        $balance = auth()->user()->wallet->balance;
    }
}
