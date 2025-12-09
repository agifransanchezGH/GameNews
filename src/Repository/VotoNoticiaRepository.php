<?php

namespace App\Repository;

use App\Entity\VotoNoticia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VotoNoticia>
 *
 * @method VotoNoticia|null find($id, $lockMode = null, $lockVersion = null)
 * @method VotoNoticia|null findOneBy(array $criteria, array $orderBy = null)
 * @method VotoNoticia[]    findAll()
 * @method VotoNoticia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VotoNoticiaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VotoNoticia::class);
    }

//    /**
//     * @return VotoNoticia[] Returns an array of VotoNoticia objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VotoNoticia
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
