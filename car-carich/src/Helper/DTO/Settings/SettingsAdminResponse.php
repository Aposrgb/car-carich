<?php

namespace App\Helper\DTO\Settings;

use App\Entity\Settings;
use App\Helper\Enum\Type\Settings as EnumSettingsType;
use App\Helper\Inteface\DTOInterface;

readonly class SettingsAdminResponse implements DTOInterface
{
    public int $id;
    public int $type;
    public string $typeName;
    public ?string $value;
    public ?string $img;

    public function __construct(Settings $settings)
    {
        $this->id = $settings->getId();
        $this->type = $settings->getType();
        $this->typeName = EnumSettingsType::tryFrom($settings->getType())?->getName();
        $this->value = $settings->getValue();
        $this->img = $settings->getImg();
    }

}