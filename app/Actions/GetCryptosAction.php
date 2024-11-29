<?php

namespace App\Actions;

use App\Models\CryptoCurrency;
use App\Models\Currency;
use App\Traits\ResponseTrait;

class GetCryptosAction
{
    use ResponseTrait;

    public function execute()
    {
        return $this->successResponse(data: ['currencies' => CryptoCurrency::paginate(20)]);
    }
}
