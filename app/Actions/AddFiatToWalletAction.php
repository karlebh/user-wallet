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
        $balance = auth()->user()->wallet()->balance;

        if (
            $balance >= config('wallet.max_fiat_ballance')
            || ($balance + $requestData['amount'] >= config('wallet.max_fiat_ballance'))
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
            'transactionable_id' => $wallet::class,
            'currency' => $wallet->code,
            'type' => TransactionType::DEPOSIT,
            'trx' => $this->generateTrxCode(),
            'amount' => $requestData['amount'],
            'note' => $requestData['note'] ?? ""
        ]);

        return $this->successResponse(
            code: 201,
            message: $wallet->currency . $requestData['amount'] . "added successfully",
            data: [
                'balance' => $wallet->balance,
            ]
        );
    }
}
