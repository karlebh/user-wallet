<?php

namespace App\Actions;

use App\Models\CryptoCurrency;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;

class AddCryptoAction
{
    public function execute(array $requestData)
    {
        if (CryptoCurrency::where('name', $requestData['name'])->exists()) {
            return response()->json([
                'status' => false,
                'message' => $requestData['name'] . ' already exists in database',
            ], 409);
        }

        $currency = CryptoCurrency::create($requestData);

        return response()->json([
            'status' => true,
            'message' => $currency->name . ' added succesfully',
        ], 201);
    }
}
