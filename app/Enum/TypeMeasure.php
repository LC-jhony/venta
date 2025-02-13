<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;

enum TypeMeasure: string implements HasLabel
{
    case UNIDAD = 'Unidad';
    case KILO = 'Kilo';
    case LIBRA = 'Libra';
    case GRAMO = 'Gramo';
    case LITRO = 'Litro';
    case MILILITRO = 'Mililitro';
    case METRO = 'Metro';
    case PULGADA = 'Pulgada';
    case ROLLO = 'Rollo';
    case GALON = 'Galon';
    case BOLSA = 'Bolsa';
    case CAJA = 'Caja';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::UNIDAD => 'Unidad',
            self::KILO => 'Kilo',
            self::LIBRA => 'Libra',
            self::GRAMO => 'Gramo',
            self::LITRO => 'Litro',
            self::MILILITRO => 'Mililitro',
            self::METRO => 'Metro',
            self::PULGADA => 'Pulgada',
            self::ROLLO => 'Rollo',
            self::GALON => 'Galon',
            self::BOLSA => 'Bolsa',
            self::CAJA => 'Caja',
        };
    }
}
