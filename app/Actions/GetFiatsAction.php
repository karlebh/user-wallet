<?php

namespace App\Actions;

use App\Models\Currency;
use App\Traits\ResponseTrait;

class GetFiatsAction
{
    use ResponseTrait;
    public function execute()
    {
        return $this->successResponse(data: ['currencies' => Currency::paginate(20)]);
    }
}
