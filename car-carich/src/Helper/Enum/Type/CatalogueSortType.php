<?php

namespace App\Helper\Enum\Type;

use App\Helper\Enum\Trait\TypeValuesTrait;

enum CatalogueSortType: int
{
    use TypeValuesTrait;

    case POPULAR = 2;
    // Подешевле
    case CHEAPER = 3;
    // Подороже
    case EXPENSIVE = 4;


}