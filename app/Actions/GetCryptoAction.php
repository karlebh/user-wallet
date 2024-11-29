<?php

namespace App\Actions;

use App\Models\CryptoCurrency;
use App\Traits\ResponseTrait;

class GetCryptoAction
{
    use ResponseTrait;

    public function execute(array $requestData)
    {
        $crypto = CryptoCurrency::query()
            ->where('code', $requestData['code'])
            ->when(!empty($requestData['name']), function ($query) use ($requestData) {
                $query->where('name', $requestData['name']);
            })
            ->get();

        return $this->successResponse(data: ['crypto' => $crypto->toArray()]);
    }
}
