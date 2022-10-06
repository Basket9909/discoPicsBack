<?php

namespace App\Repository;

use App\Entity\FavoritePublication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FavoritePublication>
 *
 * @method FavoritePublication|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoritePublication|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoritePublication[]    findAll()
 * @method FavoritePublication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoritePublicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoritePublication::class);
    }

    // public function getPublicationForUserWithMaxResult(int $id): array
    // {
    //     return $this->createQueryBuilder('p')
    //         ->select('p as publication, u as user')
    //         ->join('p.user', 'u')
    //         ->andWhere('u.id = :id')
    //         ->setParameter(':id', $id)
    //         ->setMaxResults(6)
    //         ->getQuery()
    //         ->getResult();
    // }

//    /**
//     * @return FavoritePublication[] Returns an array of FavoritePublication objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FavoritePublication
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
