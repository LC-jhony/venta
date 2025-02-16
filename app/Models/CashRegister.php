<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashRegister extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'initial_amount',
        'final_amount',
        'notes',
        'open_date',
        'close_date',
        'status',
    ];
    protected $casts = [
        'open_date' => 'date',
        'close_date' => 'date',
        'status' => 'boolean',
        'initial_amount' => 'decimal:2',

    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function totals(): HasMany
    {
        return $this->hasMany(CashRegisterTotal::class);
    }

    // public function getSaleTotalAttribute()
    // {
    //     return $this->totals()->sum('sale_total');
    // }

    // public function getPurchaseTotalAttribute()
    // {
    //     return $this->totals()->sum('purchase_total');
    // }

    // public function getCurrentBalanceAttribute()
    // {
    //     return $this->initial_amount + $this->sale_total - $this->purchase_total;
    // }
}
