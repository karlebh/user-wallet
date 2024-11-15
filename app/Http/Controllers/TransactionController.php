<?php

namespace App\Http\Controllers;

use App\Actions\AddCryptoAction;
use App\Actions\AddMoneyAction;
use App\Actions\CheckBalanceAction;
use App\Actions\SendMoneyAction;
use App\Actions\TransactionHistoryAction;
use App\Actions\WithdrawalAction;
use App\Http\Requests\SendMoneyRequest;
use App\Http\Requests\TransactionRequest;
use App\Http\Requests\WithdrawalRequest;
use Exception;
use App\Actions\BuyCryptoAction;
use App\Http\Requests\AddCryptoRequest;
use App\Http\Requests\AddMoneyRequest;
use App\Http\Requests\BuyCryptoRequest;

class TransactionController extends Controller
{
    public function sendMoney(SendMoneyRequest $request)
    {
        try {
            return (new SendMoneyAction())->execute($request->validated());
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function checkBalance()
    {
        try {
            return (new CheckBalanceAction())->execute();
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function transactions(TransactionRequest $request)
    {
        try {
            return (new TransactionHistoryAction())->execute($request->validated());
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function withdraw(WithdrawalRequest $request)
    {
        try {
            return (new WithdrawalAction())->execute($request->validated());
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function buyCrypto(BuyCryptoRequest $request)
    {
        try {
            return (new BuyCryptoAction())->execute($request->validated());
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function addMoney(AddMoneyRequest $request)
    {
        try {
            return (new AddMoneyAction())->execute($request->validated());
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
