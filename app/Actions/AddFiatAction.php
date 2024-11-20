<?php

namespace App\Actions;

use App\Models\Currency;
use App\Traits\ResponseTrait;

class AddFiatAction
{
    use ResponseTrait;
    public function execute(array $requestData)
    {
        if (Currency::where('name', $requestData['name'])->exists()) {

            return $this->errorResponse(
                code: 409,
                message: $requestData['name'] . ' already exists in database',
            );
        }

        $currency = Currency::create($requestData);

        return response()->json([
            'status' => true,
            'message' => $currency->name . ' added succesfully',
        ], 201);
    }
}
