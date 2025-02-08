<?php

namespace App\Models;

use App\Models\Quote;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Suppliers extends Model
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
}
