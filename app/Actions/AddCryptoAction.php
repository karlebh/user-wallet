<?php

namespace App\Actions;

use App\Models\CryptoCurrency;
use App\Traits\ResponseTrait;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;

class AddCryptoAction
{
    use ResponseTrait;

    public function execute(array $requestData)
    {
        if (CryptoCurrency::where('name', $requestData['name'])->exists()) {

            return $this->errorResponse(
                message: $requestData['name'] . ' already exists in database',
                code: 409,
            );
        }

        $currency = CryptoCurrency::create($requestData);

        return $this->successResponse(
            code: 201,
            message: $currency->name . ' added succesfully',
            data: [
                $currency
            ]
        );
    }
}