<?php


namespace App\Actions;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class CheckFiatBalanceAction
{
    public function execute()
    {
        $balance = Wallet::where('user_id', auth()->id())->first()->balance;

        return response()->json([
            'status' => true,
            'balance' => $balance,
        ], 200);
    }
}
