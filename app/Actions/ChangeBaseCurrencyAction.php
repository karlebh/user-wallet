<?php

namespace App\Actions;

use App\Models\Currency;
use App\Models\Wallet;
use App\Traits\ResponseTrait;

class ChangeBaseCurrencyAction
{
    use ResponseTrait;

    public function execute(array $requestData)
    {
        $wallet = auth()->user()->wallet;

        $balance = $wallet->balance;
        $currency = $wallet->name;

        $data = Currency::whereCode($requestData['code'])->first();

        $newBalance = ($balance / $wallet->exchange_rate) * $data->exchange_rate;

        $wallet->update([
            'balance' => $newBalance,
            'code' => $data->code,
            'currency' => $data->currency,
        ]);

        return $this->successResponse(
            message: 'Base currency successfully changed to ' . $data->code,
            data: [$data->balance],
        );
    }
}