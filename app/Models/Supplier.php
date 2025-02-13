<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    /** @use HasFactory<\Database\Factories\SuppliersFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'status',
        'description',
        'country',
        'city',
        'state',
    ];

    public function quote()
    {
        return $this->hasMany(
            related: Quote::class,
            foreignKey: 'supplier_id',
        );
    }

    public function purchase(): HasMany
    {
        return $this->hasMany(
            related: Purchase::class,
            foreignKey: 'supplier_id',
        );
    }
}
