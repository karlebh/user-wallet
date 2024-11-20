<?php

namespace App\Actions;

use App\Models\Transaction;
use App\Traits\ResponseTrait;

class TransactionHistoryAction
{
    use ResponseTrait;

    public function execute($requestData)
    {
        if (auth()->user()->transactions()->count() === 0) {
            return $this->errorResponse(message: 'User has no transactions yet');
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

        return $this->successResponse(
            message: 'Transactions retrieved succesfully',
            data: ['transactions' => $transactions]
        );
    }
}
