<?php

namespace App\Helper\Enum\Type;

enum Car: int
{
    case ELECTRONIC = 1;
    case HYBRID = 2;
    case PETROL = 3;
    case DIESEL = 4;

    public static function getTypes(): array
    {
        return [
            self::ELECTRONIC->value,
            self::HYBRID->value,
            self::PETROL->value,
            self::DIESEL->value,
        ];
    }


    public static function getNames(): array
    {
        return [
            self::ELECTRONIC->value => 'Электро',
            self::HYBRID->value => 'Гибрид',
            self::PETROL->value => 'Бензин',
            self::DIESEL->value => 'Дизель',
        ];
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