<?php

namespace App\Helper\Components;

use App\Entity\Settings;
use App\Helper\Enum\Type\Settings as SettingsType;
use App\Repository\SettingsRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('social-network-modal')]
class SocialNetworkModal
{
    public ?Settings $settings;
    public function __construct(
        private SettingsRepository $settingsRepository,
    )
    {
        $this->settings = $this->settingsRepository->findOneBy(['type' => SettingsType::WHATSAPP->value]);
    }

}