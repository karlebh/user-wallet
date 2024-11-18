<?php

namespace App\Actions;

use App\Models\Currency;

class AddFiatAction
{
    public function execute($requestData)
    {
        $currency = Currency::create($requestData);

        return response()->json([
            'status' => true,
            'message' => $currency->name . ' created succesfully',
        ], 201);
    }
}
