<?php

namespace App\Actions;

use App\Enums\TransactionType;
use App\Models\CryptoCurrency;
use App\Models\CryptoWallet;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Traits\ResponseTrait;
use App\Traits\UtilityHelper;

class BuyCryptoAction
{
    use UtilityHelper;
    use ResponseTrait;

    public function execute(array $requestData)
    {
        $fiat_balance = auth()->user()->wallet()->balance;

        if ($fiat_balance < 1) {
            return $this->errorResponse(message: 'Your balance ' . $fiat_balance . ' is insufficient');
        }

        $dollar_rate = auth()->user()->wallet()->exchange_rate;

        $crypto = CryptoCurrency::query()
            ->whereCode($requestData['code'])
            ->orWhereName($requestData['name'])
            ->first();

        $to_usd = $fiat_balance / $dollar_rate;
        $to_crypto = $to_usd / $crypto->exchange_rate;

        $wallet = auth()->user()->cryptoWallet;

        if ($wallet->code !== $requestData['code']) {
            auth()->user()->cryptoWallet()->create([
                'currency' => $requestData['name'],
                'code' => $requestData['code'],
                'balance' => $to_crypto,
            ]);

            Transaction::create([
                'user_id' => auth()->id(),
                'transactionable_id' => $wallet->id,
                'transactionable_id' => $wallet::class,
                'currency' => $wallet->code,
                'type' => TransactionType::PURCHASE,
                'trx' => $this->generateTrxCode(),
                'amount' => $to_crypto,
                'note' => $requestData['note'] ?? ""
            ]);

            return $this->successResponse(
                code: 201,
                message: $crypto->name . " Purchased successfully",
                data: ['crypto_balance' => $to_crypto]
            );
        }

        $wallet = CryptoWallet::query()
            ->where('code', $requestData['code'])
            ->where('user_id', auth()->id())
            ->first();

        $wallet->increment('balance', $to_crypto);

        Transaction::create([
            'user_id' => auth()->id(),
            'transactionable_id' => $wallet->id,
            'transactionable_id' => $wallet::class,
            'currency' => $wallet->code,
            'type' => TransactionType::PURCHASE,
            'trx' => $this->generateTrxCode(),
            'amount' => $to_crypto,
            'note' => $requestData['note'] ?? ""
        ]);

        return $this->successResponse(
            message: $crypto->name . " Purchased successfully",
            data: [
                'crypto_balance' => $to_crypto,
            ],
        );
    }
}
