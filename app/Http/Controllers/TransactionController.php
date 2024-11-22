<?php

namespace App\Http\Controllers;

use App\Actions\AddCryptoAction;
use App\Actions\AddCryptoToWalletAction;
use App\Actions\AddFiatToWalletAction;
use App\Actions\AddMoneyAction;
use App\Actions\AddMoneyToWalletAction;
use App\Actions\CheckBalanceAction;
use App\Actions\SendMoneyAction;
use App\Actions\TransactionHistoryAction;
use App\Actions\WithdrawalAction;
use App\Http\Requests\SendMoneyRequest;
use App\Http\Requests\TransactionRequest;
use App\Http\Requests\WithdrawalRequest;
use Exception;
use App\Actions\BuyCryptoAction;
use App\Actions\CheckCryptoBalanceAction;
use App\Actions\CheckFiatBalanceAction;
use App\Actions\GetCryptoAction;
use App\Actions\SellCryptoAction;
use App\Http\Requests\AddCryptoRequest;
use App\Http\Requests\AddCryptoToWalletRequest;
use App\Http\Requests\AddFiatToWalletRequest;
use App\Http\Requests\AddMoneyRequest;
use App\Http\Requests\BuyCryptoRequest;
use App\Http\Requests\CheckCryptoBalanceRequest;
use App\Http\Requests\GetCryptoRequest;
use App\Http\Requests\SellCryptoRequest;
use App\Traits\ResponseTrait;

class TransactionController extends Controller
{
    use ResponseTrait;

    public function sendMoney(SendMoneyRequest $request)
    {
        try {
            return (new SendMoneyAction())->execute($request->validated());
        } catch (Exception $exception) {
            return $this->errorResponse(message: $exception->getMessage());
        }
    }

    public function checkFiatBalance()
    {
        try {
            return (new CheckFiatBalanceAction())->execute();
        } catch (Exception $exception) {
            return $this->errorResponse(message: $exception->getMessage());
        }
    }

    public function checkCryptoBalance(CheckCryptoBalanceRequest $request)
    {
        try {
            return (new CheckCryptoBalanceAction())->execute($request->validated());
        } catch (Exception $exception) {
            return $this->errorResponse(message: $exception->getMessage());
        }
    }

    public function transactions(TransactionRequest $request)
    {
        try {
            return (new TransactionHistoryAction())->execute($request->validated());
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'execption' => $exception,
                'class' => $exception,
            ], 500);
        }
    }

    public function withdraw(WithdrawalRequest $request)
    {
        try {
            return (new WithdrawalAction())->execute($request->validated());
        } catch (Exception $exception) {
            return $this->errorResponse(message: $exception->getMessage());
        }
    }

    public function buyCrypto(BuyCryptoRequest $request)
    {
        try {
            return (new BuyCryptoAction())->execute($request->validated());
        } catch (Exception $exception) {
            return $this->errorResponse(message: $exception->getMessage());
        }
    }

    public function sellCrypto(SellCryptoRequest $request)
    {
        try {
            return (new SellCryptoAction())->execute($request->va);
        } catch (Exception $exception) {
            return $this->errorResponse(message: $exception->getMessage());
        }
    }

    public function addMoney(AddFiatToWalletRequest $request)
    {
        try {
            return (new AddFiatToWalletAction())->execute($request->validated());
        } catch (Exception $exception) {
            return $this->errorResponse(message: $exception->getMessage());
        }
    }

    public function addCrypto(AddCryptoToWalletRequest $request)
    {
        try {
            return (new AddCryptoToWalletAction())->execute($request->validated());
        } catch (Exception $exception) {
            return $this->errorResponse(message: $exception->getMessage());
        }
    }

    public function getCrypto(GetCryptoRequest $request)
    {
        try {
            return (new GetCryptoAction())->execute($request->validated());
        } catch (Exception $exception) {
            return $this->errorResponse(message: $exception->getMessage());
        }
    }
}
