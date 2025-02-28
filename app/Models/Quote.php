<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'number_quote',
        'notes',
        'valid_date',
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

    // public function supplier()
    // {
    //     return $this->belongsTo(Supplier::class);
    // }
    public function suppliers()
    {
        return $this->belongsToMany(
            related: Supplier::class,
            table: 'quote_suppliers',
            foreignPivotKey: 'quote_id',
            relatedPivotKey: 'supplier_id'
        );
    }

    public function quoteProducts(): HasMany
    {
        return $this->hasMany(
            QuoteProduct::class,
        );
    }

    public function quoteSuppliers(): HasMany
    {
        return $this->hasMany(
            related: QuoteSupplier::class,
        );
    }
}
