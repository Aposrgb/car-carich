<?php

namespace App\Helper\Enum\Trait;

trait TypeValuesTrait
{
    public static function getTypeValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getTypeValuesToString(): array
    {
        return array_map(fn(mixed $type) => (string)$type, self::getTypeValues());
    }

}