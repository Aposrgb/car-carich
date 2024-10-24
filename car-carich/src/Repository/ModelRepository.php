<?php

namespace App\Repository;

use App\Entity\Model;
use App\Repository\Trait\FindIdNameArray;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Model>
 *
 * @method Model|null find($id, $lockMode = null, $lockVersion = null)
 * @method Model|null findOneBy(array $criteria, array $orderBy = null)
 * @method Model[]    findAll()
 * @method Model[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModelRepository extends ServiceEntityRepository
{
    use FindIdNameArray;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Model::class);
    }
}
