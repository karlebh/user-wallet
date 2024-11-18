<?php

namespace App\Actions;

use App\Models\Wallet;

class AddMoneyAction
{
    public function execute($requestData)
    {
        $balance = auth()->user()->wallet()->balance;

        if (
            $balance >= config('wallet.max_fiat_ballance')
            || ($balance + $requestData['amount'] >= config('wallet.max_fiat_ballance'))
        ) {
            return response()->json([
                'status' => false,
                'message' => 'You can not have more than a billion in an account',
            ], 500);
        }

        auth()->user()->wallet()->increment('balance', $requestData['amount']);
        $currency = auth()->user()->wallet->currency;

        return response()->json([
            'status' => true,
            'balance' => $currency . $requestData['amount'] . "added successfully",
        ], 201);
    }
}
