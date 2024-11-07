<?php

namespace App\Http\Controllers;

use App\Actions\CheckBalanceAction;
use App\Actions\SendMoneyAction;
use App\Http\Requests\SendMoneyRequest;
use Exception;

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
}
