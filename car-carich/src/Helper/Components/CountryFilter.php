<?php

namespace App\Helper\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('filters/country')]
class CountryFilter
{
    public function __construct(
        public array $countries = [
            '0' => 'Все страны',
            '1' => 'Россия',
            '2' => 'Китай',
            '3' => 'Узбекистан',
        ],
    )
    {
    }

}