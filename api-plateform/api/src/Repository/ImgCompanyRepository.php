<?php

namespace App\Repository;

use App\Entity\ImgCompany;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImgCompany>
 *
 * @method ImgCompany|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImgCompany|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImgCompany[]    findAll()
 * @method ImgCompany[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImgCompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImgCompany::class);
    }

//    /**
//     * @return ImgCompany[] Returns an array of ImgCompany objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ImgCompany
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
