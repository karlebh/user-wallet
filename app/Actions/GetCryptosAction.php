<?php

namespace App\Actions;

use App\Models\CryptoCurrency;
use App\Models\Currency;

class GetCryptosAction
{
    public function execute()
    {
        return response()->json(['currencies' => CryptoCurrency::paginate(20)]);
    }
}
