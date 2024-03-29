<?php

namespace App\Helper\Components;

use App\Repository\SettingsRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('header')]
class Header
{
    public function __construct(
        public SettingsRepository $settingsRepository,
        public bool               $nameHidden = false,
        public bool               $isDisplayNone = false,
        public bool               $mainHidden = false,
        public bool               $catalogHidden = false,
        public bool               $aboutUsHidden = false,
        public array              $settings = [],
    )
    {
        $this->settings = $this->settingsRepository->findBy([], ['type' => 'ASC']);
    }

}