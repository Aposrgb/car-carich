<?php

namespace App\Repository\Trait;

use App\Helper\Filter\Pagination;
use Doctrine\ORM\{QueryBuilder, Tools\Pagination\Paginator};

/**
 * @template T of object
 */
trait BaseRepositoryTrait
{
    /**
     * @param T $entity
     * @param bool $flush
     * @return void
     */
    public function add($entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param T $entity
     * @param bool $flush
     * @return void
     */
    public function remove($entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    private function getPaginatorByQueryBuilder(QueryBuilder $queryBuilder, Pagination $pagination): Paginator
    {
        return new Paginator(
            $queryBuilder
                ->setFirstResult($pagination->getFirstMaxResult())
                ->setMaxResults($pagination->getLimit())
        );
    }
}
