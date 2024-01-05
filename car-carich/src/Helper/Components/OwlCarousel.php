<?php

namespace App\Helper\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('owl_carousel')]
class OwlCarousel
{
    public function __construct(
        public ?string $classNameNextArrow = null,
        public ?string $classNamePrevArrow = null,
        public ?string $selectorName = null,
        public ?int $items = 1,
        public ?int $margin = 0,
    )
    {
    }

}