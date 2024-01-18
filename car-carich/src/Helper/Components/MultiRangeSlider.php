<?php

namespace App\Helper\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('filters/multirange')]
class MultiRangeSlider
{
    public function __construct(
        public string $symbol = '₽',
        public string $name = 'Ценовой диапазон',
        public string $label = 'multi',
        public int $min = 0,
        public int $max = 100,
        public bool $createSlider = true,
    )
    {
    }

}