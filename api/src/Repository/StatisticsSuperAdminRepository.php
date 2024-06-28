<?php

namespace App\Repository;

use App\Entity\Statistics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Statistics>
 *
 * @method Statistics|null find($id, $lockMode = null, $lockVersion = null)
 * @method Statistics|null findOneBy(array $criteria, array $orderBy = null)
 * @method Statistics[]    findAll()
 * @method Statistics[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatisticsSuperAdminRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statistics::class);
    }

    public function getStatistics(): Statistics
    {
        $totalReservations = $this->getTotalReservations();
        $totalClients = $this->getTotalClients();
        $todaysReservations = $this->getTodaysReservations();
        $weeklyReservations = $this->getWeeklyReservations();
        $monthlyReservations = $this->getMonthlyReservations();
        $activeReservations = $this->getTotalActiveReservations();
        $cancelledReservations = $this->getTotalCancelledReservations();
        $distinctClientsPerWeek = $this->getDistinctClientsWithReservationsPerWeek();
        $totalServicesPerCompany = $this->getTotalServicesPerCompany();

        $statistics = new Statistics();
        $statistics->setTotalReservations($totalReservations);
        $statistics->setTotalClients($totalClients);
        $statistics->setTodaysReservations($todaysReservations);
        $statistics->setWeeklyReservations($weeklyReservations);
        $statistics->setMonthlyReservations($monthlyReservations);
        $statistics->setTotalActiveReservations($activeReservations);
        $statistics->setTotalCancelledReservations($cancelledReservations);
        $statistics->setDistinctClientsWithReservationsPerWeek($distinctClientsPerWeek);
        $statistics->setTotalServicesPerCompany($totalServicesPerCompany);

        return $statistics;
    }

    public function getTotalReservations(): int
    {
        return $this->_em->createQuery('SELECT COUNT(r.id) FROM App\Entity\Booking r')->getSingleScalarResult();
    }

    public function getTotalClients(): int
    {
        return $this->_em->createQuery('SELECT COUNT(c.id) FROM App\Entity\User c')->getSingleScalarResult();
    }

    public function getTodaysReservations(): int
    {
        $date = new \DateTime();
        return $this->_em->createQuery('SELECT COUNT(r.id) FROM App\Entity\Booking r WHERE r.startDate LIKE :today')
            ->setParameter('today', $date->format('Y-m-d') . '%')
            ->getSingleScalarResult();
    }

    public function getWeeklyReservations(): int
    {
        $date = new \DateTime();
        $startOfWeek = $date->modify('last monday')->format('Y-m-d');
        $endOfWeek = $date->modify('next sunday')->format('Y-m-d');

        return $this->_em->createQuery('SELECT COUNT(r.id) FROM App\Entity\Booking r WHERE r.startDate BETWEEN :startOfWeek AND :endOfWeek')
            ->setParameter('startOfWeek', $startOfWeek)
            ->setParameter('endOfWeek', $endOfWeek)
            ->getSingleScalarResult();
    }


    public function getMonthlyReservations(): int
    {
        $startDate = new \DateTime('first day of this month');
        $endDate = new \DateTime('last day of this month');

        return $this->_em->createQuery('
            SELECT COUNT(r.id) 
            FROM App\Entity\Booking r 
            WHERE r.startDate BETWEEN :startDate AND :endDate
        ')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getSingleScalarResult();
    }



    public function getTotalActiveReservations(): int
    {
        return $this->_em->createQuery('
            SELECT COUNT(b.id) 
            FROM App\Entity\Booking b 
            WHERE b.status = :status
        ')
            ->setParameter('status', 'pending')
            ->getSingleScalarResult();
    }

    public function getTotalCancelledReservations(): int
    {
        return $this->_em->createQuery('
        SELECT COUNT(b.id) 
        FROM App\Entity\Booking b 
        WHERE b.status = :status
    ')
            ->setParameter('status', 'cancelled')
            ->getSingleScalarResult();
    }

    public function getDistinctClientsWithReservationsPerWeek(): int
    {
        $startDate = new \DateTime('monday this week');
        $endDate = new \DateTime('sunday this week');

        return $this->_em->createQuery('
            SELECT COUNT(DISTINCT b.client)
            FROM App\Entity\Booking b
            WHERE b.startDate BETWEEN :startDate AND :endDate
        ')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getSingleScalarResult();
    }

    public function getTotalServicesPerCompany(): array
    {
        $query = $this->_em->createQuery('
        SELECT c.name as companyName, COUNT(cs.id) as totalServices
        FROM App\Entity\Company c
        JOIN c.companyServices cs
        GROUP BY c.name
    ');

        $results = $query->getResult();

        $servicesPerCompany = [];

        foreach ($results as $result) {
            $companyName = $result['companyName'];
            $totalServices = $result['totalServices'];

            $servicesPerCompany[$companyName] = $totalServices;
        }

        return $servicesPerCompany;
    }
}
