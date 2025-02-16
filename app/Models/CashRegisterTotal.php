<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashRegisterTotal extends Model
{
    protected $fillable = [
        'cash_register_id',
        'sale_total',
        'purchase_total',
    ];
    protected $casts = [
        'sale_total' => 'decimal:2',
        'purchase_total' => 'decimal:2',
    ];

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }
    
}
