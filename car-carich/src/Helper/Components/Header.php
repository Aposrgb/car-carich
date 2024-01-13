<?php

namespace App\Helper\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('header')]
class Header
{
    public function __construct(
        public bool $nameHidden = false,
        public bool $isDisplayNone = false,
        public bool $mainHidden = false,
        public bool $catalogHidden = false,
        public bool $aboutUsHidden = false,
    )
    {
    }

}