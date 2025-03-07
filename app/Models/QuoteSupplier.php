<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuoteSupplier extends Model
{
    // use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'supplier_id',
    ];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(
            related: Quote::class,
            foreignKey: 'quote_id'
        );
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(
            related: Supplier::class,
            foreignKey: 'supplier_id'
        );
    }
}
