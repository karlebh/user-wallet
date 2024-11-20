<?php

namespace App\Actions;

use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Traits\ResponseTrait;
use App\Traits\UtilityHelper;

class WithdrawalAction
{
    use UtilityHelper, ResponseTrait;

    public function execute(array $requestData)
    {
        $balance = auth()->user()->wallet->amount;

        if ($requestData['amount'] > $balance) {
            return $this->errorResponse(message: 'Insufficient Funds');
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
            'note' => $requestData['note']
        ]);

        return $this->successResponse(
            message: $wallet->code . $requestData['amount'] . " withdrawn successfully",
            data: ['balance' => $balance]
        );
    }
}
