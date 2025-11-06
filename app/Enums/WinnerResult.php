<?php

namespace App\Enums;

enum WinnerResult: string
{
    case X = 'X';
    case O = 'O';
    case DRAW = 'Draw';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
