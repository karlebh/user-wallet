<?php

use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\TransactionController;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/all-fiats', [CurrencyController::class, 'getFiats']);
Route::get('/all-cryptos', [CurrencyController::class, 'getCryptos']);
Route::get('/crypto', [CurrencyController::class, 'getCrypto']);
Route::get('/get-crypto', [CurrencyController::class, 'getCrypto']);
Route::get('/fiat', [CurrencyController::class, 'getFiat']);
Route::get('/fiat', [CurrencyController::class, 'getFiat']);
Route::post('/add-fiat', [CurrencyController::class, 'addFiat']);
Route::post('/add-crypto', [CurrencyController::class, 'addCrypto']);


Route::middleware(['auth:sanctum'])
    ->group(
        function () {
            Route::get('/user', fn(Request $request) => $request->user());

            Route::post('/send-money', [TransactionController::class, 'sendMoney']);
            Route::get('/check-fiat-balance', [TransactionController::class, 'checkFiatBalance']);
            Route::get('/check-crypto-balance', [TransactionController::class, 'checkCryptoBalance']);
            Route::get('/transactions', [TransactionController::class, 'transactions']);
            Route::post('/withdraw', [TransactionController::class, 'withdraw']);
            Route::post('/buy-crypto', [TransactionController::class, 'buyCrypto']);
            Route::post('/add-money', [TransactionController::class, 'addMoney']);
        }
    );





require __DIR__ . '/auth.php';
