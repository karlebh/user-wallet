<?php

namespace App\Actions;

use App\Models\Currency;

class AddFiatAction
{
    public function execute(array $requestData)
    {
        if (Currency::where('name', $requestData['name'])->exists()) {
            return response()->json([
                'status' => false,
                'message' => $requestData['name'] . ' already exists in database',
            ], 409);
        }

        $currency = Currency::create($requestData);

        return response()->json([
            'status' => true,
            'message' => $currency->name . ' added succesfully',
        ], 201);
    }
}
