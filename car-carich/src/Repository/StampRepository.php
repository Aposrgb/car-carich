<?php

namespace App\Repository;

use App\Entity\Stamp;
use App\Repository\Trait\FindBySelectPropertyNameTrait;
use App\Repository\Trait\FindIdNameArray;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Stamp>
 *
 * @method Stamp|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stamp|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stamp[]    findAll()
 * @method Stamp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StampRepository extends ServiceEntityRepository
{
    use FindBySelectPropertyNameTrait, FindIdNameArray;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stamp::class);
    }
}
