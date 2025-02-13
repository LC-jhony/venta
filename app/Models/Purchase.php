<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'supplier_id',
        'purchase_number',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id'
        );
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(
            related: Supplier::class,
            foreignKey: 'supplier_id'
        );
    }

    public function detailparchuse(): HasMany
    {
        return $this->hasMany(PurchaseDetail::class);
    }
}
