<?php

namespace App\Actions;

use App\Models\Currency;

class GetFiatAction
{
    public function execute(array $requestData)
    {
        $currency = Currency::where('code', $requestData['code'])->get();
        return response()->json(['curreny' => $currency]);
    }
}
