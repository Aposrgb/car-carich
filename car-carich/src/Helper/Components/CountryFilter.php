<?php

namespace App\Helper\Components;

use App\Repository\CountryRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('filters/country')]
class CountryFilter
{
    public function __construct(
        private readonly CountryRepository $countryRepository,
        public array                       $countries = [],
    )
    {
        $this->countries = $this->countryRepository->findAll();
    }

}