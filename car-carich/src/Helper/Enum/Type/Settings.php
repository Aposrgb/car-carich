<?php

namespace App\Helper\Enum\Type;

enum Settings: int
{
    case CONTRACT = 1;
    case VK_URL = 2;
    case INST_URL = 3;
    case YOU_URL = 4;

    public static function getNamesForFormType(): array
    {
        return [
            'Договор' => self::CONTRACT->value,
            'Ссылка на ВК' => self::VK_URL->value,
            'Ссылка на Instagram' => self::INST_URL->value,
            'Ссылка на Youtube' => self::YOU_URL->value,
        ];
    }

    public function getName(): string
    {
        return match ($this) {
            self::CONTRACT => 'Договор',
            self::VK_URL => 'Ссылка на ВК',
            self::INST_URL => 'Ссылка на Instagram',
            self::YOU_URL => 'Ссылка на Youtube',
        };
    }
}