<?php

namespace App\Actions;

use App\Models\CryptoWallet;
use App\Traits\ResponseTrait;

class CheckCryptoBalanceAction
{
    use ResponseTrait;

    public function execute(array $requestData)
    {
        $balance = CryptoWallet::query()
            ->where('user_id', auth()->id())
            ->where('code', $requestData['code'])
            ->when(!empty($requestData['name']), function ($query) use ($requestData) {
                $query->where('name', $requestData['name']);
            })
            ->pluck('balance');

        return $this->successResponse(
            data: [
                'balance' => $balance->toArray(),
            ]
        );
    }
}
