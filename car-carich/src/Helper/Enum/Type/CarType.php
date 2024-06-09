<?php

namespace App\Helper\Enum\Type;

enum CarType: int
{
    case ELECTRONIC = 1;
    case HYBRID = 2;
    case PETROL = 3;
    case DIESEL = 4;

    public static function getNames(): array
    {
        return [
            self::ELECTRONIC->value => 'Электро',
            self::HYBRID->value => 'Гибрид',
            self::PETROL->value => 'Бензин',
            self::DIESEL->value => 'Дизель',
        ];
    }

    public static function getNamesLower(): array
    {
        return array_map('mb_strtolower', self::getNames());
    }

    public static function getNamesForFormType(): array
    {
        return [
            'Электро' => self::ELECTRONIC->value,
            'Гибрид' => self::HYBRID->value,
            'Бензин' => self::PETROL->value,
            'Дизель' => self::DIESEL->value,
        ];
    }

    public function getName(): string
    {
        return self::getNames()[$this->value];
    }
}