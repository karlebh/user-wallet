<?php

namespace Database\Seeders;

use App\Models\CryptoCurrency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CryptoCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cryptocurrencies = [
            [
                "name" => "Bitcoin",
                "code" => "BTC",
                "exchange_rate" => 28000
            ],
            [
                "name" => "Ethereum",
                "code" => "ETH",
                "exchange_rate" => 1800
            ],
            [
                "name" => "USD Tether",
                "code" => "USDT",
                "exchange_rate" => 1
            ],
            [
                "name" => "USD Coin",
                "code" => "USDC",
                "exchange_rate" => 1
            ],
            [
                "name" => "Binance Coin",
                "code" => "BNB",
                "exchange_rate" => 300
            ],
            [
                "name" => "XRP",
                "code" => "XRP",
                "exchange_rate" => 0.50
            ],
            [
                "name" => "Cardano",
                "code" => "ADA",
                "exchange_rate" => 0.30
            ],
            [
                "name" => "Dogecoin",
                "code" => "DOGE",
                "exchange_rate" => 0.07
            ],
            [
                "name" => "Polygon",
                "code" => "MATIC",
                "exchange_rate" => 0.70
            ],
            [
                "name" => "Solana",
                "code" => "SOL",
                "exchange_rate" => 20
            ]
        ];

        CryptoCurrency::insert($cryptocurrencies);
    }
}
