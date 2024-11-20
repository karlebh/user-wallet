<?php

namespace App\Actions;

use App\Models\Currency;
use App\Traits\ResponseTrait;

class GetFiatAction
{
    use ResponseTrait;

    public function execute(array $requestData)
    {
        $currency = Currency::where('code', $requestData['code'])->get();
        return $this->successResponse(data: ['curreny' => $currency]);
    }
}
