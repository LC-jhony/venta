<?php

namespace App\Enum\CashMovement;

use Filament\Support\Contracts\HasLabel;

enum MovementType: string implements HasLabel
{
    case Entrada = 'Entrada';
    case Salida = 'Salida';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Entrada => 'Entrada',
            self::Salida => 'Salida',
        };
    }
}
