<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuoteProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'quote_id',
        'product_id',
        'quantity',
        'price_unit',
        'total_price',
    ];

    public function quote(): BelongsTo
    {
        return $this->belongsTo(
            related: Quote::class,
            foreignKey: 'quote_id'
        );
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(
            related: Product::class,
            foreignKey: 'product_id'
        );
    }
}
