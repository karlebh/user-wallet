<?php

namespace App\Actions;

use App\Enums\TransactionType;
use App\Models\CryptoCurrency;
use App\Models\CryptoWallet;
use App\Models\Currency;
use App\Models\Transaction;
use App\Traits\ResponseTrait;
use App\Traits\UtilityHelper;
use phpDocumentor\Reflection\Types\This;

class BuyCryptoAction
{
    use UtilityHelper;
    use ResponseTrait;

    private static string $defaultCrypto = "BTC";

    public function execute(array $requestData)
    {
        $user = auth()->user();
        $wallet = $user->wallet;

        // Validate balance
        $fiat_balance = $wallet->balance;
        if ($fiat_balance < 1 || (!empty($requestData['amount']) && $requestData['amount'] > $fiat_balance)) {
            return $this->errorResponse('Your balance is insufficient.');
        }

        // Get cryptocurrency
        $crypto = $this->getCryptoCurrency($requestData);

        // Calculate USD and crypto amounts
        $dollar_rate = Currency::whereCode($wallet->code)->value('exchange_rate');

        $to_usd = !empty($requestData['amount'])
            ? ($requestData['amount'] / $dollar_rate)
            : ($fiat_balance / $dollar_rate);

        $to_crypto = $this->round($to_usd / $crypto->exchange_rate);

        // Deduct fiat wallet balance
        $this->updateFiatWallet($wallet, $requestData['amount'] ?? $fiat_balance);

        // Update or create crypto wallet
        $cryptoWallet = $this->updateCryptoWallet($user, $crypto, $to_crypto);

        return $this->successResponse(
            message: "{$to_crypto} {$crypto->code} purchased successfully.",
            data: ['wallet' => $cryptoWallet]
        );
    }

    private function getCryptoCurrency(array $data)
    {
        return CryptoCurrency::query()
            ->whereCode(data_get($data, 'code', static::$defaultCrypto))
            ->orWhere('name', data_get($data, 'name'))
            ->first();
    }

    private function updateFiatWallet($wallet, $amount)
    {
        $wallet->decrement('balance', $amount);
        Transaction::create([
            'user_id' => auth()->id(),
            'transactionable_id' => $wallet->id,
            'transactionable_type' => $wallet::class,
            'currency' => $wallet->code,
            'type' => TransactionType::PURCHASE,
            'trx' => $this->generateTrxCode(),
            'amount' => $amount,
            'note' => $requestData['node'] ?? '',
        ]);
    }

    private function updateCryptoWallet($user, $crypto, $to_crypto)
    {
        $cryptoWallet = $user->cryptoWallets()->firstOrCreate(
            ['code' => $crypto->code],
            ['name' => $crypto->name, 'balance' => 0]
        );

        $cryptoWallet->increment('balance', $to_crypto);

        Transaction::create([
            'user_id' => auth()->id(),
            'transactionable_id' => $cryptoWallet->id,
            'transactionable_type' => $cryptoWallet::class,
            'currency' => $cryptoWallet->code,
            'type' => TransactionType::DEPOSIT,
            'trx' => $this->generateTrxCode(),
            'amount' => $to_crypto,
            'note' => $requestData['node'] ?? '',
        ]);

        return $cryptoWallet;
    }



    // public function execute(array $requestData)
    // {
    //     $fiat_balance = auth()->user()->wallet->balance;

    //     if ($fiat_balance < 1 || (!empty($requestData['amount']) && $requestData['amount'] > $fiat_balance)) {
    //         return $this->errorResponse(message: 'Your balance ' . $fiat_balance . ' is insufficient');
    //     }

    //     $dollar_rate = Currency::whereCode(auth()->user()->wallet->code)->first()->exchange_rate;

    //     $crypto = null;

    //     if (! empty($requestData['code']) || ! empty($requestData['name'])) {
    //         $crypto =
    //             CryptoCurrency::query()
    //             ->whereCode($requestData['code'])
    //             ->orWhere'name', ($requestData['name'])
    //             ->first();
    //     } else {
    //         $crypto =
    //             CryptoCurrency::query()
    //             ->whereCode(static::$defaultCrypto)
    //             ->first();
    //     }

    //     $to_usd = ! empty($requestData['amount'])
    //         ? ($requestData['amount'] / $dollar_rate)
    //         : ($fiat_balance / $dollar_rate);
    //     $to_crypto = $this->round($to_usd / $crypto->exchange_rate);

    //     if (! empty($requestData['amount'])) {
    //         $wallet = auth()->user()->wallet;
    //         $wallet->decrement($requestData['amount']);

    //         Transaction::create([
    //             'user_id' => auth()->id(),
    //             'transactionable_id' => $wallet->id,
    //             'transactionable_type' => $wallet::class,
    //             'currency' => $wallet->code,
    //             'type' => TransactionType::PURCHASE,
    //             'trx' => $this->generateTrxCode(),
    //             'amount' => $to_crypto,
    //             'note' => $requestData['note'] ?? ""
    //         ]);
    //     } else {

    //         $wallet = auth()->user()->wallet;

    //         $wallet->update(['balance' => 0]);

    //         Transaction::create([
    //             'user_id' => auth()->id(),
    //             'transactionable_id' => $wallet->id,
    //             'transactionable_type' => $wallet::class,
    //             'currency' => $wallet->code,
    //             'type' => TransactionType::PURCHASE,
    //             'trx' => $this->generateTrxCode(),
    //             'amount' => $to_crypto,
    //             'note' => $requestData['note'] ?? ""
    //         ]);
    //     }

    //     if (empty($requestData['code']) || $requestData['code'] === 'BTC') {

    //         $wallet = auth()->user()->cryptoWallets()->whereCode('BTC');
    //         $wallet->increment('balance', $to_crypto);

    //         Transaction::create([
    //             'user_id' => auth()->id(),
    //             'transactionable_id' => $wallet->id,
    //             'transactionable_type' => $wallet::class,
    //             'currency' => $wallet->code,
    //             'type' => TransactionType::DEPOSIT,
    //             'trx' => $this->generateTrxCode(),
    //             'amount' => $to_crypto,
    //             'note' => $requestData['note'] ?? ""
    //         ]);

    //         return $this->successResponse(
    //             message: $to_crypto . $wallet->code . ' purchased successfully',
    //             data: ['wallet' => $wallet]
    //         );
    //     } else if (auth()->user()->cryptoWallets()->whereCode($requestData['code'])->exists()) {
    //         $wallet = auth()->user()->cryptoWallets()->whereCode($requestData['code']);
    //         $wallet->increment('balance', $to_crypto);

    //         Transaction::create([
    //             'user_id' => auth()->id(),
    //             'transactionable_id' => $wallet->id,
    //             'transactionable_type' => $wallet::class,
    //             'currency' => $wallet->code,
    //             'type' => TransactionType::DEPOSIT,
    //             'trx' => $this->generateTrxCode(),
    //             'amount' => $to_crypto,
    //             'note' => $requestData['note'] ?? ""
    //         ]);

    //         return $this->successResponse(
    //             message: $to_crypto . $wallet->code . ' purchased successfully',
    //             data: ['wallet' => $wallet]
    //         );
    //     } else {
    //         $wallet = auth()->user()->cryptoWallets()->create(['name' => $crypto->name, 'code' => $crypto->code, 'balance' => $to_crypto]);

    //         Transaction::create([
    //             'user_id' => auth()->id(),
    //             'transactionable_id' => $wallet->id,
    //             'transactionable_type' => $wallet::class,
    //             'currency' => $wallet->code,
    //             'type' => TransactionType::DEPOSIT,
    //             'trx' => $this->generateTrxCode(),
    //             'amount' => $to_crypto,
    //             'note' => $requestData['note'] ?? ""
    //         ]);

    //         return $this->successResponse(
    //             message: $to_crypto . $wallet->code . ' purchased successfully',
    //             data: ['wallet' => $wallet]
    //         );
    //     }
    // }
}
