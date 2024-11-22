<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'transactionable_type',
        'transactionable_id',
        'currency',
        "trx",
        'type',
        'note',
        'amount',
    ];

    protected function walletId(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->transactionable_id
        );
    }

    protected function walletType(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->transactionable_type
        );
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }
}
