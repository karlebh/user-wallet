<?php

namespace App\Actions;

use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Traits\UtilityHelper;

class AddCryptoToWalletAction
{
    use UtilityHelper;

    public function execute(array $requestData)
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

        $crypto_wallet = auth()->user()->cryptoWallet()
            ->where('code', $requestData['code'])
            ->exsits();

        if (! $crypto_wallet) {
            return response()->json([
                'status' => false,
                'message' => 'You do not have any ' . $crypto_wallet->code,
            ], 404);
        }

        $crypto_wallet->increment('balance', $requestData['amount']);

        Transaction::create([
            'user_id' => auth()->id(),
            'transactionable_id' => auth()->user()->wallet->id,
            'transactionable_type' => auth()->user()->wallet::class,
            'currency' => $requestData['code'] ?? "BTC",
            'type' => TransactionType::DEPOSIT,
            'trx' => $this->generateTrxCode(),
            'amount' => $requestData['amount'],
            'note' => $requestData['note'] ?? ""
        ]);

        return response()->json([
            'status' => true,
            'balance' =>  $requestData['amount'] . $requestData['code'] . " added successfully",
        ], 201);
    }
}
