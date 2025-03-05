<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'supplier_id',
        'quote_id',
        'purchase_number',
        'status',
        'total',
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
    public function quote(): BelongsTo
    {
        return $this->belongsTo(
            related: Quote::class,
            foreignKey: 'quote_id'
        );
    }

    public function detailparchuse(): HasMany
    {
        return $this->hasMany(PurchaseDetail::class);
    }
}
