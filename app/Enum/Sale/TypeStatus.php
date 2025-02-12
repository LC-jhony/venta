<?php

namespace App\Enum\Sale;

use Filament\Support\Contracts\HasLabel;

enum TypeStatus: string implements HasLabel
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Partial = 'partial';
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => 'pending',
            self::Paid => 'paid',
            self::Partial => 'partial',
        };
    }
}
