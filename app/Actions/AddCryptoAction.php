<?php

namespace App\Actions;

class AddCryptoAction
{
    public function execute($requestData)
    {
        $balance = auth()->user()->cryptoWallet()->balance;

        if (
            $balance >= config('wallet.max_crypto_balance')
            || ($balance + $requestData['amount'] >= config('wallet.max_crypto_balance'))
        ) {
            return response()->json([
                'status' => false,
                'message' => 'You can not have more than a billion in an account',
            ], 500);
        }

        auth()->user()->wallet()->cryptoWallet('balance', $requestData['amount']);
        $currency = auth()->user()->wallet->currency;

        return response()->json([
            'status' => true,
            'balance' => $currency . $requestData['amount'] . "added successfully",
        ], 201);
    }
}
