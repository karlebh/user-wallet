<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoCurrency extends Model
{
    protected $table = 'crypto_currencies';

    protected $fillable = [
        'name',
        'currency',
        'code',
        'exchange_rate',
    ];
}
