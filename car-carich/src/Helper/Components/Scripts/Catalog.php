<?php

namespace App\Helper\Components\Scripts;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('scripts/catalog')]
class Catalog
{
    public function __construct(
        public int $minPrice = 0,
        public int $maxPrice = 0,
        public int $maxMileage = 0,
        public int $minMileage = 0,
    )
    {
    }
}