<?php

namespace App\Actions;

class WithdrawalAction
{
    public function execute($requestData)
    {
        $balance = auth()->user()->wallet->amount;

        if ($requestData['amount'] > $balance) {
            return response()->json([
                'status' => false,
                'message' => 'Insufficient Funds',
            ], 500);
        }

        $balance -= $requestData['amount'];
        auth()->user()->wallet->update(['amount' => $balance]);

        return response()->json([
            'status' => true,
            'message' => $requestData['amount'] . " withdrawn successfully",
            'balance' => $balance,
        ], 500);
    }
}
