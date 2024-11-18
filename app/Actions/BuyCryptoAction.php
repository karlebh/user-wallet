<?php

namespace App\Actions;

use App\Models\CryptoCurrency;
use App\Models\CryptoWallet;
use App\Models\Wallet;

class BuyCryptoAction
{
    public function execute($requestData)
    {
        $fiat_balance = auth()->user()->wallet()->balance;

        if ($fiat_balance < 1) {
            return response()->json([
                'status' => false,
                'message' => 'Your balance ' . $fiat_balance . ' is insufficient',
            ], 500);
        }

        $dollar_rate = auth()->user()->wallet()->exchange_rate;

        $crypto = CryptoCurrency::query()
            ->whereName($requestData['code'])
            ->orWhereCode($requestData['name'])
            ->first();

        $to_usd = $fiat_balance / $dollar_rate;
        $to_crypto = $to_usd / $crypto->exchange_rate;

        if (auth()->user()->cryptoWallet->code !== $requestData['code']) {
            auth()->user()->cryptoWallet()->create([
                'currency' => $requestData['name'],
                'code' => $requestData['code'],
                'balance' => $to_crypto,
            ]);

            return response()->json([
                'status' => true,
                'message' => $crypto->name . " Purchased successfully",
                'crypto_balance' => $to_crypto,
            ], 201);
        }

        CryptoWallet::query()
            ->where('code', $requestData['code'])
            ->where('user_id', auth()->id())
            ->increment('balance', $to_crypto);

        return response()->json([
            'status' => true,
            'message' => $crypto->name . " Purchased successfully",
            'crypto_balance' => $to_crypto,
        ], 201);
    }
}
