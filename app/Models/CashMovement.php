<?php

namespace App\Models;

use App\Models\CashRegister;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashMovement extends Model
{
    protected $fillable = [
        'cash_register_id',
        'type',
        'amount',
        'description',
    ];

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(
            related: CashRegister::class,
            foreignKey: 'cash_register_id',
        );
    }
}
