<?php

namespace App\Models;

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

    // protected static function booted(): void
    // {
    //     static::creating(function ($detailparchuse) {
    //         $product = $detailparchuse->product;
    //         $product->stock += $detailparchuse->quantity;
    //         $product->save();
    //     });
    // }
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($detailparchuse) {
            if ($detailparchuse->purchase->status == '1') {
                $product = $detailparchuse->product;
                $product->stock += $detailparchuse->quantity;
                $product->save();
            }
        });

        static::updating(function ($detailparchuse) {
            $product = $detailparchuse->product;

            // Si el status cambió de true a false, revertir el stock
            if ($detailparchuse->purchase->status == '0' && $detailparchuse->getOriginal('quantity')) {
                $product->stock -= $detailparchuse->getOriginal('quantity');
            }
            // Si el status cambió de false a true, sumar al stock
            elseif ($detailparchuse->purchase->status == '1') {
                $product->stock += $detailparchuse->quantity;
            }

            $product->save();
        });

        static::deleting(function ($detailparchuse) {
            if ($detailparchuse->purchase->status == '1') {
                $product = $detailparchuse->product;
                $product->stock -= $detailparchuse->quantity;
                $product->save();
            }
        });
    }
}
