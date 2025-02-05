<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;
    use  SoftDeletes;
    protected $fillable = [
        'name',
        'lastname',
        'dni',
        'email',
        'phone',
        'address',
    ];
}
