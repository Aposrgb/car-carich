<?php

namespace App\Helper\Filter;

readonly class CatalogueFilter
{
    public function __construct(
        public string $type = '',
        public string $country = '',
        public string $minPrice = '',
        public string $maxPrice = '',
        public string $minMileage = '',
        public string $maxMileage = '',
    )
    {
    }
}