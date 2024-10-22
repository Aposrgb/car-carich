<?php

namespace App\Repository;

use App\Entity\Car;
use App\Helper\Enum\Type\CatalogueSortType;
use App\Helper\Filter\CatalogueFilter;
use App\Helper\Filter\Pagination;
use App\Repository\Trait\FindBySelectPropertyNameTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    use FindBySelectPropertyNameTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function getPaginatorCatalogue(CatalogueFilter $catalogueFilter, Pagination $pagination): Paginator
    {
        $qb = $this->createQueryBuilder('c');
        if (!empty($catalogueFilter->type)) {
            $qb
                ->where($qb->expr()->in('c.typeEngine', ':types'))
                ->setParameter('types', explode(',', $catalogueFilter->type));
        }
        if (!empty($catalogueFilter->country)) {
            $qb
                ->andWhere('c.country = :country')
                ->setParameter('country', $catalogueFilter->country);
        }
        if (!empty($catalogueFilter->maxPrice) and is_numeric($catalogueFilter->maxPrice)) {
            $qb
                ->andWhere('c.fullPrice <= :maxPrice')
                ->setParameter('maxPrice', $catalogueFilter->maxPrice);
        }
        if (!empty($catalogueFilter->minPrice) and is_numeric($catalogueFilter->minPrice)) {
            $qb
                ->andWhere('c.fullPrice >= :minPrice')
                ->setParameter('minPrice', $catalogueFilter->minPrice);
        }
        if (!empty($catalogueFilter->maxMileage) and is_numeric($catalogueFilter->maxMileage)) {
            $qb
                ->andWhere('c.mileage <= :maxMileage')
                ->setParameter('maxMileage', $catalogueFilter->maxMileage);
        }
        if (!empty($catalogueFilter->minMileage) and is_numeric($catalogueFilter->minMileage)) {
            $qb
                ->andWhere('c.mileage >= :minMileage')
                ->setParameter('minMileage', $catalogueFilter->minMileage);
        }

        if (!empty($catalogueFilter->text)) {
            $qb
                ->andWhere($qb->expr()->like('LOWER(c.name)', ':name'))
                ->setParameter('name', '%' . mb_strtolower($catalogueFilter->text) . '%');
        }

        if (empty($catalogueFilter->sort)) {
            $qb->orderBy('c.mileage', 'DESC');
        } elseif ($catalogueFilter->sort == CatalogueSortType::POPULAR->value) {
            $qb->addSelect(
                '
                (
                    CASE WHEN c.isPopular = true THEN 0
                    ELSE 1 END
                ) AS HIDDEN popular
           '
            )->orderBy('popular', 'ASc');
        } elseif ($catalogueFilter->sort == CatalogueSortType::CHEAPER->value) {
            $qb->orderBy('c.fullPrice', 'ASC');
        } elseif ($catalogueFilter->sort == CatalogueSortType::EXPENSIVE->value) {
            $qb->orderBy('c.fullPrice', 'DESC');
        }

        return $this->getPaginatorByQueryBuilder($qb, $pagination);
    }

    public function getPaginatorPopularCars(Pagination $pagination): Paginator
    {
        return $this->getPaginatorByQueryBuilder(
            $this
                ->createQueryBuilder('c')
                ->where('c.isPopular = true'),
            $pagination
        );
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
