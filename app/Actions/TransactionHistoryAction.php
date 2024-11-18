<?php

namespace App\Actions;

use App\Models\Transaction;

class TransactionHistoryAction
{
    public function execute($requestData)
    {
        if (auth()->user()->transactions()->count() === 0) {
            return response()->json([
                'status' => true,
                'message' => 'User has no transactions yet',
            ], 204);
        }

        $transactions = auth()->user()->transactions()
            ->when(!empty($requestData['type']), function ($query) use ($requestData) {
                $query->where('type', $requestData['type']);
            })
            ->when(!empty($requestData['date']), function ($query) use ($requestData) {
                // $query->whereRaw('HOUR(created_at) = ?', [$requestData['date']]);
                $query->whereDate('created_at', $requestData['date']);
            })
            ->when(!empty($requestData['currency']), function ($query) use ($requestData) {
                $query->where('currency', $requestData['currency']);
            })
            ->get();


        return response()->json([
            'status' => true,
            'message' => 'Transactions retrieved succesfully',
            'transactions' => $transactions,
        ], 200);
    }
}
