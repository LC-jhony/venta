<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseDetail extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'unit_cost',
        // 'subtotal'
    ];
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(
            related: Purchase::class,
            foreignKey: 'purchase_id'
        );
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(
            related: Product::class,
            foreignKey: 'product_id'
        );
    }
    protected static function booted(): void
    {
        static::creating(function ($detailparchuse) {
            $product = $detailparchuse->product;
            $product->stock += $detailparchuse->quantity;
            $product->save();
        });
    }
}
