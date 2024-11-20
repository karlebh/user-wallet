<?php


namespace App\Actions;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;

class CheckFiatBalanceAction
{
    use ResponseTrait;

    public function execute()
    {
        $balance = Wallet::where('user_id', auth()->id())->first()->balance;

        return $this->successResponse(
            data: ['balance' => $balance,]
        );
    }
}
