<?php

namespace App\Enum\Sale;

use Filament\Support\Contracts\HasLabel;

enum TypePyment: string implements HasLabel
{
    case Cash = 'cash';
    case Card = 'card';
    case Transfer = 'transfer';
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Cash => 'Cash',
            self::Card => 'Card',
            self::Transfer => 'Transfer',
        };
    }
}
