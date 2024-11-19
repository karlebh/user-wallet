<?php


namespace App\Actions;

use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\UtilityHelper;
use Illuminate\Support\Facades\DB;

class SendMoneyAction
{
    use UtilityHelper;

    public function execute(array $requestData)
    {
        //Add rate limiter to route
        $to_user = User::with('wallet')
            ->where('id', $requestData['reciever_id'])
            ->first();

        auth()->user()->load('wallet');

        if ($to_user->id === auth()->id()) {
            return response()->json([
                'status' => false,
                'message' => 'Can not send money to self',
            ], 500);
        }

        if ($requestData['amount'] > auth()->user()->wallet->balance) {
            return response()->json([
                'status' => false,
                'message' => 'Insufficient funds',
            ], 500);
        }

        $transaction = DB::transaction(function () use ($to_user, $requestData) {

            $new_balance = auth()->user()->wallet->balance - $requestData['amount'];
            auth()->user()->wallet->update(['balance' => $new_balance]);

            $to_user->wallet->balance += $requestData['amount'];
            $to_user->wallet->save();

            $user = auth()->user();

            // For sender
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'transactionable_id' => $user->wallet->id,
                'transactionable_id' => $user->wallet::class,
                'currency' => $user->wallet->code,
                'type' => TransactionType::DEBIT,
                'trx' => $this->generateTrxCode(),
                'amount' => $requestData['amount'],
                'note' => $requestData['note'] ?? ""
            ]);

            // For receiver
            Transaction::create([
                'user_id' => $to_user->id,
                'transactionable_id' => $to_user->wallet->id,
                'transactionable_id' => $to_user->wallet::class,
                'currency' => $to_user->wallet->code,
                'type' => TransactionType::CREDIT,
                'trx' => $this->generateTrxCode(),
                'amount' => $requestData['amount'],
                'note' => $requestData['note'] ?? ""
            ]);

            return $transaction;
        });

        return response()->json([
            'status' => true,
            'message' => 'Transaction Successful!',
            'transaction_id' => $transaction->id,
            'trx' => $transaction->trx,
        ], 201);
    }
}
