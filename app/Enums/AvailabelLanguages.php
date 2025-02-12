<?php

namespace App\Enums;

enum AvailabelLanguages : string
{
    case English = 'en';
    case Arabic = 'ar';
    case French = 'fr';

    public static function values(): array
    {
        return [
            self::English,
            self::Arabic,
            self::French,
        ];
    }

}
