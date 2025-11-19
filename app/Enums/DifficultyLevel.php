<?php

namespace App\Enums;

enum DifficultyLevel: string
{
    case EASY = 'Easy';
    case NORMAL = 'Normal';
    case HARD = 'Hard';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
