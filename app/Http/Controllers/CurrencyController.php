<?php

namespace App\Http\Controllers;

use App\Actions\AddFiatAction;
use App\Actions\GetCryptosAction;
use App\Actions\GetFiatsAction;
use App\Http\Requests\AddFiatRequest;
use App\Models\CryptoCurrency;
use App\Models\Currency;
use Exception;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function getFiats()
    {
        try {
            return (new GetFiatsAction())->execute();
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getCryptos()
    {
        try {
            return (new GetCryptosAction())->execute();
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function addFiat(AddFiatRequest $request)
    {
        try {
            return (new AddFiatAction())->execute($request->validated());
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
    public function addCrypto() {}
}
