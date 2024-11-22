<?php

namespace App\Actions;

use App\Models\Currency;
use App\Traits\ResponseTrait;

class AddFiatAction
{
    use ResponseTrait;
    public function execute(array $requestData)
    {
        $currency = Currency::query()
            ->where('name', $requestData['name'])
            ->orWhere('country', $requestData['country'])
            ->orWhere('code', $requestData['code'])
            ->exists();

        if ($currency) {
            return $this->errorResponse(
                code: 409,
                message: $requestData['name'] . ' already exists in database',
            );
        }

        $currency = Currency::create($requestData);

        return $this->successResponse(
            message: $currency->name . ' added succesfully',
            code: 201,
            data: [
                'currency' => $currency
            ]
        );
    }
}
