<?php


namespace App\Actions;

use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SendMoneyAction
{
    private  function generateTrxCode($prefix = 'trx')
    {
        return $prefix . '-' . time() . '-' . uniqid();
    }

    public function execute(array $requestData)
    {
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

        $transaction_id = null;

        DB::transaction(function () use ($to_user, $requestData, &$transaction_id) {

            $new_balance = auth()->user()->wallet->balance - $requestData['amount'];
            auth()->user()->wallet->update(['balance' => $new_balance]);

            $to_user->wallet->balance += $requestData['amount'];
            $to_user->wallet->save();

            // For sender
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'wallet_id' => auth()->user()->wallet->id,
                'currency' => $requestData['currency'] ?? "NGN",
                'type' => TransactionType::DEBIT,
                'trx' => $this->generateTrxCode(),
                'amount' => $requestData['amount'],
                'note' => $requestData['note'] ?? ""
            ]);

            // For receiver
            Transaction::create([
                'user_id' => $to_user->id,
                'wallet_id' => $to_user->wallet->id,
                'currency' => $requestData['currency'] ?? "NGN",
                'type' => TransactionType::CREDIT,
                'trx' => $this->generateTrxCode(),
                'amount' => $requestData['amount'],
                'note' => $requestData['note'] ?? ""
            ]);

            $transaction_id =  $transaction->id;
        });

        return response()->json([
            'status' => true,
            'message' => 'Transaction Successful!',
            'transaction_id' => $transaction_id
        ], 201);
    }
}
