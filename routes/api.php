<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])
    ->group(
        function () {
            Route::get('/user', function (Request $request) {
                return $request->user();
            });
            Route::post('/send-money', [TransactionController::class, 'sendMoney']);
            Route::get('/check-balance', [TransactionController::class, 'checkBalance']);
        }
    );





require __DIR__ . '/auth.php';
