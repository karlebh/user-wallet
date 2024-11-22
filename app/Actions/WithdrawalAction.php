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

        $amount = (float) $requestData['amount'];

        if ($amount > auth()->user()->wallet->balance) {
            return $this->errorResponse(message: 'Insufficient Funds');
        }

        $wallet = auth()->user()->wallet;

        auth()->user()->wallet->decrement('balance', $amount);

        Transaction::create([
            'user_id' => auth()->id(),
            'transactionable_id' => $wallet->id,
            'transactionable_type' => $wallet::class,
            'currency' => $wallet->code,
            'type' => TransactionType::WITHDRAWAL,
            'trx' => $this->generateTrxCode(),
            'amount' => $amount,
            'note' => $requestData['note'] ?? ''
        ]);

        return $this->successResponse(
            message: $wallet->code . $amount . " withdrawn successfully",
            data: ['balance' => $wallet->balance]
        );
    }
}
