<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'logo',
        'commercial_name',
        'company_name',
        'type_company',
        'ruc',
        'address',
        'phone',
        'email',
        'web',
        'district',
        'department',
        'province',
    ];
}
