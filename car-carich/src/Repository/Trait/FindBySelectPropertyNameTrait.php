<?php

namespace App\Repository\Trait;

trait FindBySelectPropertyNameTrait
{
    public function findBySelectPropertyName(string $propertyName = 'name', ?array $criteria = [])
    {
        $qb = $this->createQueryBuilder('e')->indexBy('e', "e.$propertyName");

        foreach ($criteria as $field => $value) {
            if (is_array($value)) {
                $qb->andWhere($qb->expr()->in("e.$field", ":$field"))
                    ->setParameter("$field", $value);
            } elseif (!empty($value)) {
                $qb->andWhere("e.$field = :$field")
                    ->setParameter("$field", $value);
            }
        }

        return $qb
            ->getQuery()
            ->getResult();
    }

}