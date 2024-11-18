<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                "country" => "Nigeria",
                "code" => "NGN",
                "name" => "Naira",
                "exchange_rate" => 1720.28
            ],
            [
                "country" => "South Africa",
                "code" => "ZAR",
                "name" => "Rand",
                "exchange_rate" => 19.02
            ],
            [
                "country" => "Egypt",
                "code" => "EGP",
                "name" => "Pound",
                "exchange_rate" => 31.03
            ],
            [
                "country" => "Kenya",
                "code" => "KES",
                "name" => "Shilling",
                "exchange_rate" => 141.52
            ],
            [
                "country" => "Ethiopia",
                "code" => "ETB",
                "name" => "Birr",
                "exchange_rate" => 54.73
            ],
            [
                "country" => "Ghana",
                "code" => "GHS",
                "name" => "Cedi",
                "exchange_rate" => 12.39
            ],
            [
                "country" => "Morocco",
                "code" => "MAD",
                "name" => "Dirham",
                "exchange_rate" => 10.0
            ],
            [
                "country" => "Algeria",
                "code" => "DZD",
                "name" => "Dinar",
                "exchange_rate" => 134.28
            ],
            [
                "country" => "Tunisia",
                "code" => "TND",
                "name" => "Dinar",
                "exchange_rate" => 3.13
            ],
            [
                "country" => "Angola",
                "code" => "AOA",
                "name" => "Kwanza",
                "exchange_rate" => 798.25
            ],
            [
                "country" => "United States of America",
                "code" => "USD",
                "name" => "Dollar",
                "exchange_rate" => 1.0
            ]
        ];


        Currency::insert($currencies);
    }
}
