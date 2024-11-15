<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->group(
        function () {
            Route::get('/user', fn(Request $request) => $request->user());
            Route::post('/send-money', [TransactionController::class, 'sendMoney']);
            Route::get('/check-balance', [TransactionController::class, 'checkBalance']);
            Route::get('/transactions', [TransactionController::class, 'transactions']);
            Route::post('/withdraw', [TransactionController::class, 'withdraw']);
            Route::post('/buy-rypto', [TransactionController::class, 'buyCrypto']);
            Route::post('/add-money', [TransactionController::class, 'addMoney']);
        }
    );





require __DIR__ . '/auth.php';
