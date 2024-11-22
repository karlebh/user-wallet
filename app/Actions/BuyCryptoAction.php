<?php

namespace App\Actions;

use App\Enums\TransactionType;
use App\Models\CryptoCurrency;
use App\Models\CryptoWallet;
use App\Models\Currency;
use App\Models\Transaction;
use App\Traits\ResponseTrait;
use App\Traits\UtilityHelper;

class BuyCryptoAction
{
    use UtilityHelper;
    use ResponseTrait;

    public function execute(array $requestData)
    {
        $fiat_balance = auth()->user()->wallet->balance;

        if ($fiat_balance < 1 || (!empty($requestData['amount']) && $requestData['amount'] > $fiat_balance)) {
            return $this->errorResponse(message: 'Your balance ' . $fiat_balance . ' is insufficient');
        }

        $dollar_rate = Currency::whereCode(auth()->user()->wallet->code)->first()->exchange_rate;

        $crypto = null;

        if (! empty($requestData['code']) || !empty($requestData['name'])) {
            $crypto =
                CryptoCurrency::query()
                ->whereCode($requestData['code'])
                ->orWhereName($requestData['name'])
                ->first();
        } else {
            $crypto =
                CryptoCurrency::query()
                ->whereCode('BTC')
                ->first();
        }

        $to_usd = ! empty($requestData['amount']) ? ($requestData['amount'] / $dollar_rate) : ($fiat_balance / $dollar_rate);
        $to_crypto = $to_usd / $crypto->exchange_rate;

        if (! empty($requestData['amount'])) {
            $wallet = auth()->user()->wallet;

            $wallet->decrement($requestData['amount']);

            Transaction::create([
                'user_id' => auth()->id(),
                'transactionable_id' => $wallet->id,
                'transactionable_type' => $wallet::class,
                'currency' => $wallet->code,
                'type' => TransactionType::PURCHASE,
                'trx' => $this->generateTrxCode(),
                'amount' => $to_crypto,
                'note' => $requestData['note'] ?? ""
            ]);
        } else {

            $wallet = auth()->user()->wallet;

            $wallet->update(['balance' => 0]);

            Transaction::create([
                'user_id' => auth()->id(),
                'transactionable_id' => $wallet->id,
                'transactionable_type' => $wallet::class,
                'currency' => $wallet->code,
                'type' => TransactionType::PURCHASE,
                'trx' => $this->generateTrxCode(),
                'amount' => $to_crypto,
                'note' => $requestData['note'] ?? ""
            ]);
        }

        if (empty($requestData['code']) || $requestData['code'] === 'BTC') {

            $wallet = auth()->user()->cryptoWallets()->whereCode('BTC');
            $wallet->increment('balance', $to_crypto);

            Transaction::create([
                'user_id' => auth()->id(),
                'transactionable_id' => $wallet->id,
                'transactionable_type' => $wallet::class,
                'currency' => $wallet->code,
                'type' => TransactionType::DEPOSIT,
                'trx' => $this->generateTrxCode(),
                'amount' => $to_crypto,
                'note' => $requestData['note'] ?? ""
            ]);

            return $this->successResponse(
                message: $to_crypto . $wallet->code . ' purchased successfully',
                data: ['wallet' => $wallet]
            );
        } else if (auth()->user()->cryptoWallets()->whereCode($requestData['code'])->exists()) {
            $wallet = auth()->user()->cryptoWallets()->whereCode($requestData['code']);
            $wallet->increment('balance', $to_crypto);

            Transaction::create([
                'user_id' => auth()->id(),
                'transactionable_id' => $wallet->id,
                'transactionable_type' => $wallet::class,
                'currency' => $wallet->code,
                'type' => TransactionType::DEPOSIT,
                'trx' => $this->generateTrxCode(),
                'amount' => $to_crypto,
                'note' => $requestData['note'] ?? ""
            ]);

            return $this->successResponse(
                message: $to_crypto . $wallet->code . ' purchased successfully',
                data: ['wallet' => $wallet]
            );
        } else {
            $wallet = auth()->user()->cryptoWallets()->create(['name' => $crypto->name, 'code' => $crypto->code, 'balance' => $to_crypto]);

            Transaction::create([
                'user_id' => auth()->id(),
                'transactionable_id' => $wallet->id,
                'transactionable_type' => $wallet::class,
                'currency' => $wallet->code,
                'type' => TransactionType::DEPOSIT,
                'trx' => $this->generateTrxCode(),
                'amount' => $to_crypto,
                'note' => $requestData['note'] ?? ""
            ]);

            return $this->successResponse(
                message: $to_crypto . $wallet->code . ' purchased successfully',
                data: ['wallet' => $wallet]
            );
        }
    }
}
