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

        if ($wallet->code === $requestData['code']) {
            return $this->errorResponse(message: 'This is already your base currency', code: 422);
        }

        $balance = $wallet->balance;

        $oldExchangeRate = Currency::whereCode($wallet->code)->first()->exchange_rate;
        $data = Currency::whereCode($requestData['code'])->first();

        // Check if both currencies exist in the array
        $amountInUSD = $balance /  $oldExchangeRate;

        // Convert from USD to target currency
        $convertedAmount = $amountInUSD * $data->exchange_rate;

        $wallet->balance = $convertedAmount;
        $wallet->code = $data->code;
        $wallet->currency = $data->country . ' ' . $data->code;
        $wallet->save();


        return $this->successResponse(
            message: 'Base currency successfully changed to ' . $data->code,
            data: ['wallet' => $wallet->fresh()],
        );
    }
}
