<?php

namespace App\Helper\Components;


use App\Repository\SettingsRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('footer')]
class Footer
{
    public function __construct(
        public SettingsRepository $settingsRepository,
        public array              $settings = [],
    )
    {
        $this->settings = $this->settingsRepository->findBy([], ['type' => 'ASC']);
    }

}