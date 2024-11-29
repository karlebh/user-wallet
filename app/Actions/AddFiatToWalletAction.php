<?php

namespace App\Actions;

use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Traits\ResponseTrait;
use App\Traits\UtilityHelper;

class AddFiatToWalletAction
{
    use UtilityHelper, ResponseTrait;

    public function execute(array $requestData)
    {
        $balance = auth()->user()->wallet->balance;

        if (
            $balance > 1_000_000_000
            || ($balance + $requestData['amount'] > 1_000_000_000)
        ) {
            return $this->errorResponse(
                message: 'You can not have more than a billion in an account',
            );
        }

        auth()->user()->wallet()->increment('balance', $requestData['amount']);
        $wallet = auth()->user()->wallet;

        Transaction::create([
            'user_id' => auth()->id(),
            'transactionable_id' => $wallet->id,
            'transactionable_type' => $wallet::class,
            'currency' => $wallet->code,
            'type' => TransactionType::DEPOSIT,
            'trx' => $this->generateTrxCode(),
            'amount' => $requestData['amount'],
            'note' => $requestData['note'] ?? ""
        ]);

        return $this->successResponse(
            code: 201,
            message: $wallet->code . ' ' . $requestData['amount'] . " added successfully",
            data: [
                'balance' => $wallet->balance,
            ]
        );
    }
}
