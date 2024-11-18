<?php

namespace App\Actions;

use App\Models\Currency;

class GetFiatsAction
{
    public function execute()
    {
        return response()->json(['currencies' => Currency::paginate(20)]);
    }
}
