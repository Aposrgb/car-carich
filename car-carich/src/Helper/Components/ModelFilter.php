<?php

namespace App\Helper\Components;

use App\Repository\ModelRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('filters/model')]
class ModelFilter
{
    public function __construct(
        ModelRepository $modelRepository,
        public array $models = [],
    ) {
        $this->models = $modelRepository->findIdNameArray();
    }

}