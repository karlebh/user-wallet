<?php

namespace App\Actions;

use App\Models\CryptoCurrency;
use App\Models\Currency;
use App\Traits\ResponseTrait;

class SellCryptoAction
{
    use ResponseTrait;

    public function execute(array $requestData)
    {
        $amount = $requestData['amount'];
        $wallet = auth()->user()->cryptoWallet()->where('code', $requestData['code']);
        $base_currency = auth()->user()->wallet->code;
        $rate = CryptoCurrency::where('code', $requestData['code'])->first()->exchange_rate;

        if ($amount > $wallet->balance) {
            return $this->errorResponse(message: 'Insufficient funds');
        }

        $wallet->decrement('balance', $requestData['amount']);

        $to_usd = $wallet->balance * $rate;

        if ($base_currency === 'USD') {
            auth()->user()->wallet()->increment('balance', $to_usd);

            return $this->successResponse(
                message: $requestData['amount'] . $requestData['code'] . "sold successfully",
                data: [
                    "crypto_balance" => $wallet->fresh()->balance,
                    'fiat_balance' => auth()->user()->wallet->balance,
                ]
            );
        }

        $dollar_rate = Currency::where('code', $base_currency)->first()->exchange_rate;
        $to_currency = $to_usd * $dollar_rate;

        auth()->user()->wallet()->increment('balance', $to_currency);

        return $this->successResponse(
            message: $requestData['amount'] . $requestData['code'] . "sold successfully",
            data: [
                "crypto_balance" => $wallet->fresh()->balance,
                'fiat_balance' => auth()->user()->wallet->fresh()->balance,
            ]
        );
    }
}
