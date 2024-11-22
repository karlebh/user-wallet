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

        $transactions = auth()->user()->transactions()->get();

        return $this->successResponse(
            message: 'Transactions retrieved succesfully',
            data: ['transactions' => $transactions]
        );
    }
}
