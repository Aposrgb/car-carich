<?php

namespace App\Repository\Trait;

trait FindIdNameArray
{
    public function findIdNameArray(): array
    {
        return $this->createQueryBuilder('e')
            ->select('e.id, e.name')
            ->getQuery()
            ->getArrayResult();
    }
}