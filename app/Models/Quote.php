<?php

namespace App\Models;

use App\Models\Suppliers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'supplier_id',
        'serial_number',
        'notes',
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
            related: Suppliers::class,
            foreignKey: 'supplier_id'
        );
    }
    public function DetailQuote(): HasMany
    {
        return $this->hasMany(DetailQuote::class);
    }
}
