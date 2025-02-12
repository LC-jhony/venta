<?php

namespace App\Models;

use App\Models\User;
use App\Models\Customer;
use App\Models\SaleDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'customer_id',
        'invoice_number',
        'subtotal',
        'tax',
        'total',
        'paid_amount',
        'change_amount',
        'payment_method',
        'payment_status',
        'sale_status',
        'notes',
    ];



    public function customer()
    {
        return $this->belongsTo(
            related: Customer::class,
            foreignKey: 'customer_id'
        );
    }
    public function user()
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id'
        );
    }
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
