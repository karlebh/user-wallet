<?php

namespace App\Actions;

use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Traits\UtilityHelper;

class WithdrawalAction
{
    use UtilityHelper;

    public function execute(array $requestData)
    {
        $balance = auth()->user()->wallet->amount;

        if ($requestData['amount'] > $balance) {
            return response()->json([
                'status' => false,
                'message' => 'Insufficient Funds',
            ], 500);
        }

        $balance -= $requestData['amount'];
        $wallet = auth()->user()->wallet;

        $wallet->update(['amount' => $balance]);

        Transaction::create([
            'user_id' => auth()->id(),
            'transactionable_id' => $wallet->id,
            'transactionable_id' => $wallet::class,
            'currency' => $wallet->code,
            'type' => TransactionType::WITHDRAWAL,
            'trx' => $this->generateTrxCode(),
            'amount' => $requestData['amount'],
            'note' => $requestData['note'] ?? ""
        ]);

        return response()->json([
            'status' => true,
            'message' => $wallet->code . $requestData['amount'] . " withdrawn successfully",
            'balance' => $balance,
        ], 500);
    }
}
