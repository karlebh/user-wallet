<?php

namespace App\Traits;

use Exception;

trait UtilityHelper
{
    protected  function generateTrxCode(string $prefix = 'trx')
    {
        return $prefix . '-' . time() . '-' . uniqid();
    }

    protected function convertCurrency($fromCode, $toCode, $amount, $currencies)
    {
        // Create a lookup table for quick access to exchange rates
        $exchangeRates = [];
        foreach ($currencies as $currency) {
            $exchangeRates[$currency['code']] = $currency['exchange_rate'];
        }

        // Check if both currencies exist in the array
        if (!isset($exchangeRates[$fromCode]) || !isset($exchangeRates[$toCode])) {
            throw new Exception("Invalid currency code(s) provided.");
        }

        // Convert amount from source currency to USD (base currency)
        $amountInUSD = $amount / $exchangeRates[$fromCode];

        // Convert from USD to target currency
        $convertedAmount = $amountInUSD * $exchangeRates[$toCode];

        return $convertedAmount;
    }
}
