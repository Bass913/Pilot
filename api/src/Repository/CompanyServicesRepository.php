<?php

namespace App\Repository;

use App\Entity\CompanyService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompanyService>
 *
 * @method CompanyService|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyService|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyService[]    findAll()
 * @method CompanyService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyServicesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanyService::class);
    }

//    /**
//     * @return CompanyService[] Returns an array of CompanyService objects
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

//    public function findOneBySomeField($value): ?CompanyService
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
