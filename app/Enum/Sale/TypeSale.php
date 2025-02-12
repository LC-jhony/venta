<?php

namespace App\Enum\Sale;

use Filament\Support\Contracts\HasLabel;

enum TypeSale: string implements HasLabel
{
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Returned = 'returned';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
            self::Returned => 'Returned',
        };
    }
}
