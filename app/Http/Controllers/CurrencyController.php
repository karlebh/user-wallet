<?php

namespace App\Http\Controllers;

use App\Actions\AddCryptoAction;
use App\Actions\AddFiatAction;
use App\Actions\ChangeBaseCurrencyAction;
use App\Actions\GetCryptoAction;
use App\Actions\GetCryptosAction;
use App\Actions\GetFiatAction;
use App\Actions\GetFiatsAction;
use App\Http\Requests\AddCryptoRequest;
use App\Http\Requests\AddFiatRequest;
use App\Http\Requests\ChangeBaseCurrencyRequest;
use App\Http\Requests\GetCryptoRequest;
use App\Http\Requests\GetFiatRequest;
use App\Models\CryptoCurrency;
use App\Models\Currency;
use Exception;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function changeBaseCurrency(ChangeBaseCurrencyRequest $request)
    {
        try {
            return (new ChangeBaseCurrencyAction())->execute($request->validated());
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

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

    public function getFiat(GetFiatRequest $request)
    {
        try {
            return (new GetFiatAction())->execute($request->validated());
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getCrypto(GetCryptoRequest $request)
    {
        try {
            return (new GetCryptoAction())->execute($request->validated());
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
    public function addCrypto(AddCryptoRequest $request)
    {
        try {
            return (new AddCryptoAction())->execute($request->validated());
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
