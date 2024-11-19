<?php

namespace App\Traits;

trait UtilityHelper
{
    protected  function generateTrxCode(string $prefix = 'trx')
    {
        return $prefix . '-' . time() . '-' . uniqid();
    }
}
