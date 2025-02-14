<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    /**
     * Get the user that the cash register belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id'
        );
    }
}
