<?php

namespace App\Repository;

use App\Entity\Coments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Coments>
 *
 * @method Coments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Coments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Coments[]    findAll()
 * @method Coments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coments::class);
    }

    public function add(Coments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Coments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getCommentsForPublication(int $id): array
    {
        return $this->createQueryBuilder('c')
            ->select('c as comment, p as publication')
            ->join('c.publication', 'p')
            ->andWhere('p.id = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Coments[] Returns an array of Coments objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Coments
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
