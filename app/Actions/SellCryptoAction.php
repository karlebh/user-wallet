<?php

namespace App\Actions;

use App\Enums\TransactionType;
use App\Models\CryptoCurrency;
use App\Models\Currency;
use App\Models\Transaction;
use App\Traits\ResponseTrait;
use App\Traits\UtilityHelper;

class SellCryptoAction
{
    use ResponseTrait;
    use UtilityHelper;

    public function execute(array $requestData)
    {
        $amount = $requestData['amount'];
        $wallet = auth()->user()->cryptoWallets()->where('code', strtoupper($requestData['code']));
        $base_currency = auth()->user()->wallet->code;
        $rate = CryptoCurrency::where('code', $requestData['code'])->value('exchange_rate');

        if ($amount > $wallet->value('balance')) {
            return $this->errorResponse(message: 'Insufficient funds');
        }

        $wallet->decrement('balance', $requestData['amount']);

        $to_usd = $this->round($wallet->value('balance') * $rate);

        if ($base_currency === 'USD') {
            auth()->user()->wallet()->increment('balance', $to_usd);

            Transaction::create([
                'user_id' => auth()->id(),
                'transactionable_id' => $wallet->value('id'),
                'transactionable_type' => $wallet::class,
                'currency' => $wallet->value('code'),
                'type' => TransactionType::TRANSFER,
                'trx' => $this->generateTrxCode(),
                'amount' => $to_usd,
                'note' => $requestData['note'] ?? ""
            ]);

            return $this->successResponse(
                message: "{$requestData['amount']} " . strtoupper($requestData['code']) . " sold successfully",
                data: [
                    "crypto_balance" => $wallet->value('balance'),
                    'fiat_balance' => auth()->user()->wallet->balance,
                ]
            );
        }

        $dollar_rate = Currency::where('code', $base_currency)->value('exchange_rate');
        $to_currency = $this->round($to_usd * $dollar_rate);

        auth()->user()->wallet()->increment('balance', $to_currency);

        Transaction::create([
            'user_id' => auth()->id(),
            'transactionable_id' => $wallet->value('id'),
            'transactionable_type' => $wallet::class,
            'currency' => $wallet->value('code'),
            'type' => TransactionType::TRANSFER,
            'trx' => $this->generateTrxCode(),
            'amount' => $to_currency,
            'note' => $requestData['note'] ?? ""
        ]);

        return $this->successResponse(
            message: "{$requestData['amount']} " . strtoupper($requestData['code']) . " sold successfully",
            data: [
                "crypto_balance" => number_format($wallet->value('balance')),
                'fiat_balance' => number_format(auth()->user()->wallet->balance),
                'crypto_account' => $wallet->first(),
                'fiat_account' => auth()->user()->wallet
            ]
        );
    }
}
