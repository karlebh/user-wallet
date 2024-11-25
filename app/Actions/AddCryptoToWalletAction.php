<?php

namespace App\Actions;

use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Traits\ResponseTrait;
use App\Traits\UtilityHelper;

class AddCryptoToWalletAction
{
    use UtilityHelper, ResponseTrait;

    public function execute(array $requestData)
    {
        $balance = auth()->user()->cryptoWallets()->whereCode(strtoupper($requestData['code']))->first()->balance;

        if (
            $balance >= config('wallet.max_crypto_balance')
            || ($balance + $requestData['amount'] >= config('wallet.max_crypto_balance'))
        ) {

            return $this->errorResponse(
                message: 'You can not have more than a billion in an account'
            );
        }

        $crypto_wallet = auth()->user()->cryptoWallets()
            ->whereCode(strtoupper($requestData['code']))
            ->first();

        if (! $crypto_wallet) {
            return $this->errorResponse(
                code: 404,
                message: 'You do not have any ' . $crypto_wallet->code
            );
        }

        $crypto_wallet->increment('balance', $requestData['amount']);

        Transaction::create([
            'user_id' => auth()->id(),
            'transactionable_id' => $crypto_wallet->id,
            'transactionable_type' => $crypto_wallet::class,
            'currency' => $crypto_wallet->code,
            'type' => TransactionType::DEPOSIT,
            'trx' => $this->generateTrxCode(),
            'amount' => $requestData['amount'],
            'note' => $requestData['note'] ?? ""
        ]);

        return $this->successResponse(
            code: 201,
            message: "{$requestData['amount']} " . strtoupper($requestData['code']) . " added successfully",
            data: [
                'balance' => $crypto_wallet->balance
            ]
        );
    }
}
