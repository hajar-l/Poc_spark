<?php

namespace App\Repository;

use App\Entity\Perimeter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Perimeter>
 *
 * @method Perimeter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Perimeter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Perimeter[]    findAll()
 * @method Perimeter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PerimeterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Perimeter::class);
    }

    public function save(Perimeter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Perimeter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getPerimetersPaginator(int $page = 1, int $limit = 10): Paginator
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.created_at', 'DESC')
            ->getQuery();

        $paginator = new Paginator($query);

        $paginator
            ->getQuery()
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return $paginator;
    }

//    /**
//     * @return Perimeter[] Returns an array of Perimeter objects
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

//    public function findOneBySomeField($value): ?Perimeter
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
