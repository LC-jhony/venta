<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'serial_number',
        'serial',
        'items',
        'notes',
        'mail',
    ];
    protected function casts()
    {
        return [
            'items' => 'json',
        ];
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id'
        );
    }
}
