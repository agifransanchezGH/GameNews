<?php

namespace App\Repository;

use App\Entity\DenunciaComentario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DenunciaComentario>
 *
 * @method DenunciaComentario|null find($id, $lockMode = null, $lockVersion = null)
 * @method DenunciaComentario|null findOneBy(array $criteria, array $orderBy = null)
 * @method DenunciaComentario[]    findAll()
 * @method DenunciaComentario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DenunciaComentarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DenunciaComentario::class);
    }

//    /**
//     * @return DenunciaComentario[] Returns an array of DenunciaComentario objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DenunciaComentario
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
