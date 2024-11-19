<?php

namespace App\Actions;

use App\Models\CryptoCurrency;

class GetCryptoAction
{
    public function execute(array $requestData)
    {
        $crypto = CryptoCurrency::query()
            ->where('code', $requestData['code'])
            ->when(!empty($requestData['name']), function ($query) use ($requestData) {
                $query->where('name', $requestData['name']);
            })
            ->get();

        return response()->json([
            'status' => true,
            'crypto' => $crypto->toArray(),
        ], 200);
    }
}
