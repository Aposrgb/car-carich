<?php

namespace App\Helper\Components\Scripts;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('scripts/index')]
class Index
{
    public function __construct(
        public int $maxPrice = 0,
    )
    {
    }

}