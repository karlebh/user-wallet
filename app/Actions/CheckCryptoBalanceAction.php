<?php

namespace App\Actions;

use App\Models\CryptoWallet;
use App\Traits\ResponseTrait;

class CheckCryptoBalanceAction
{
    use ResponseTrait;

    public function execute()
    {
        $wallets = CryptoWallet::whereUserId(auth()->id())->select(['code', 'balance', 'name'])->get();

        return $this->successResponse(
            data: [
                'wallet' => $wallets,
            ]
        );
    }
}
