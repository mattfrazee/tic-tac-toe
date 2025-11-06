<?php

namespace App\Enums;

enum PlayerMark: string
{
    case X = 'X';
    case O = 'O';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
