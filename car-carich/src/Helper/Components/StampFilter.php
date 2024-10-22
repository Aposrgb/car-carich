<?php

namespace App\Helper\Components;

use App\Repository\StampRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('filters/stamp')]
class StampFilter
{
    public function __construct(
        StampRepository $stampRepository,
        public array $stamps = [],
    ) {
        $this->stamps = $stampRepository->findIdNameArray();
    }

}