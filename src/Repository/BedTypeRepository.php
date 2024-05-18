<?php

namespace App\Repository;

use App\Entity\BedType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BedType>
 *
 * @method BedType|null find($id, $lockMode = null, $lockVersion = null)
 * @method BedType|null findOneBy(array $criteria, array $orderBy = null)
 * @method BedType[]    findAll()
 * @method BedType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BedTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BedType::class);
    }

    //    /**
    //     * @return BedTypeType[] Returns an array of BedTypeType objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?BedTypeType
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
