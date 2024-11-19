<?php

namespace App\Actions;

use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Traits\UtilityHelper;

class AddFiatToWalletAction
{
    use UtilityHelper;

    public function execute(array $requestData)
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
        $wallet = auth()->user()->wallet;

        Transaction::create([
            'user_id' => auth()->id(),
            'transactionable_id' => $wallet->id,
            'transactionable_id' => $wallet::class,
            'currency' => $wallet->code,
            'type' => TransactionType::DEPOSIT,
            'trx' => $this->generateTrxCode(),
            'amount' => $requestData['amount'],
            'note' => $requestData['note'] ?? ""
        ]);

        return response()->json([
            'status' => true,
            'balance' => $wallet->currency . $requestData['amount'] . "added successfully",
        ], 201);
    }
}
