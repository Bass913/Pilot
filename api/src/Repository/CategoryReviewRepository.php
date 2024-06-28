<?php

namespace App\Repository;

use App\Entity\CategoryReview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryReview>
 *
 * @method CategoryReview|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryReview|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryReview[]    findAll()
 * @method CategoryReview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryReview::class);
    }

//    /**
//     * @return CategoryReview[] Returns an array of CategoryReview objects
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

//    public function findOneBySomeField($value): ?CategoryReview
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
