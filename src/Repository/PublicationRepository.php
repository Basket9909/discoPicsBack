<?php

namespace App\Repository;

use App\Entity\Publication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Publication>
 *
 * @method Publication|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publication|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publication[]    findAll()
 * @method Publication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publication::class);
    }

    public function add(Publication $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Publication $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findBestPubli(int $limit): array
    {
        return $this->createQueryBuilder('p')
            ->select('p as publication, AVG(r.rate) as avgRatings')
            ->join('p.ratings', 'r')
            ->groupBy('p')
            ->orderBy('avgRatings', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getPublicationForUserWithMaxResult(int $id): array
    {
        return $this->createQueryBuilder('p')
            ->select('p as publication, u as user')
            ->join('p.user', 'u')
            ->andWhere('u.id = :id')
            ->setParameter(':id', $id)
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }

    public function getPublicationForUserWithAllResult(int $id): array
    {
        return $this->createQueryBuilder('p')
            ->select('p as publication, u as user')
            ->join('p.user', 'u')
            ->andWhere('u.id = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->getResult();
    }

    

    public function search($words){
        $query = $this->createQueryBuilder('p');
        if($words != null){
            $query->andWhere('MATCH_AGAINST(p.name, p.city, p.country, p.adress) AGAINST(:words boolean)>0')
                  ->setParameter(':words', $words);
        }
        return $query->getQuery()
                    ->getResult();
    }
//    /**
//     * @return Publication[] Returns an array of Publication objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Publication
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
