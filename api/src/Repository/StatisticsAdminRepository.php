<?php

namespace App\Repository;

use App\Entity\Statistics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class StatisticsAdminRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statistics::class);
    }

    public function getProviderStatistics(string $providerId): array
    {
        $totalEmployees = $this->getEmployeesForCompany($providerId);
        $totalReservations = $this->getTotalReservationsForProvider($providerId);
        $todaysReservations = $this->getTodaysReservations($providerId);
        $weeklyReservations = $this->getWeeklyReservations($providerId);
        $monthlyReservations = $this->getMonthlyReservations($providerId);
        $activeReservations = $this->getActiveReservations($providerId);
        $cancelledReservations = $this->getCancelledReservations($providerId);
        $distinctServices = $this->getDistinctServices($providerId);

        return [
            'totalReservations' => $totalReservations,
            'totalEmployees' => $totalEmployees,
            'todaysReservations' => $todaysReservations,
            'weeklyReservations' => $weeklyReservations,
            'monthlyReservations' => $monthlyReservations,
            'activeReservations' => $activeReservations,
            'cancelledReservations' => $cancelledReservations,
            'distinctServices' => $distinctServices
        ];
    }

    private function getCompanyIdsForProvider(string $providerId): array
    {

        $companyIds = $this->_em->createQuery('
        SELECT c.id
        FROM App\Entity\Company c
        JOIN c.user u
        WHERE u.id = :providerId')
            ->setParameter('providerId', $providerId)
            ->getResult();

        // Extraire les IDs des résultats
        $companyIdsArray = array_map(function ($company) {
            return $company['id'];
        }, $companyIds);

        return $companyIdsArray;
    }

    private function getTotalReservationsForProvider(string $providerId): int
    {
        $companyIdsArray = $this->getCompanyIdsForProvider($providerId);

        if (empty($companyIdsArray)) {
            return 0;
        }

        // Compter les réservations pour les IDs des companies récupérées
        $query = $this->_em->createQuery('
            SELECT COUNT(b.id)
            FROM App\Entity\Booking b
            JOIN b.company c
            WHERE c.id IN (:companyIds)
        ')
            ->setParameter('companyIds', $companyIdsArray);

        try {
            $reservationCount = $query->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $reservationCount = 0;
        }
        return (int) $reservationCount;
    }


    public function getEmployeesForCompany(string $providerId): array
    {

        $companyIds = $this->getCompanyIdsForProvider($providerId);

        if (empty($companyIds)) {
            return [];
        }

        $query = $this->_em->createQuery('
            SELECT u.firstname, u.lastname
            FROM App\Entity\User u
            WHERE u.company = :companyId
        ')
            ->setParameter('companyId', $companyIds[0]);
        $result = $query->getResult();


        return $result;
    }

    public function getTodaysReservations(string $providerId): int
    {
        $companyIdsArray = $this->getCompanyIdsForProvider($providerId);

        if (empty($companyIdsArray)) {
            return 0;
        }

        $query = $this->_em->createQuery('
        SELECT COUNT(b.id)
        FROM App\Entity\Booking b
        JOIN b.company c
        WHERE c.id IN (:companyIds) AND DATE_DIFF(CURRENT_DATE(), b.startDate) = 0
    ')
            ->setParameter('companyIds', $companyIdsArray);

        try {
            $todaysReservationsCount = $query->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $todaysReservationsCount = 0;
        }

        return (int) $todaysReservationsCount;
    }

    public function getWeeklyReservations(string $providerId): int
    {
        $companyIdsArray = $this->getCompanyIdsForProvider($providerId);

        if (empty($companyIdsArray)) {
            return 0;
        }

        $currentDate = new \DateTime();
        $startOfWeek = $currentDate->modify('monday this week')->format('Y-m-d');
        $endOfWeek = $currentDate->modify('sunday this week')->format('Y-m-d');

        $query = $this->_em->createQuery('
        SELECT COUNT(b.id)
        FROM App\Entity\Booking b
        JOIN b.company c
        WHERE c.id IN (:companyIds) AND b.startDate BETWEEN :startOfWeek AND :endOfWeek
    ')
            ->setParameter('companyIds', $companyIdsArray)
            ->setParameter('startOfWeek', $startOfWeek)
            ->setParameter('endOfWeek', $endOfWeek);

        try {
            $weeklyReservationsCount = $query->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $weeklyReservationsCount = 0;
        }

        return (int) $weeklyReservationsCount;
    }

    public function getMonthlyReservations(string $providerId): int
    {
        // Récupérer les IDs des companies pour le providerId
        $companyIdsArray = $this->getCompanyIdsForProvider($providerId);

        if (empty($companyIdsArray)) {
            return 0;
        }

        // Calcul des dates pour le mois courant
        $currentDate = new \DateTime();
        $startOfMonth = $currentDate->format('Y-m-01');
        $endOfMonth = $currentDate->format('Y-m-t');

        $query = $this->_em->createQuery('
        SELECT COUNT(b.id)
        FROM App\Entity\Booking b
        JOIN b.company c
        WHERE c.id IN (:companyIds) AND b.startDate BETWEEN :startOfMonth AND :endOfMonth
    ')
            ->setParameter('companyIds', $companyIdsArray)
            ->setParameter('startOfMonth', $startOfMonth)
            ->setParameter('endOfMonth', $endOfMonth);

        try {
            $monthlyReservationsCount = $query->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $monthlyReservationsCount = 0;
        }

        return (int) $monthlyReservationsCount;
    }

    public function getActiveReservations(string $providerId): int
    {
        // Récupérer les IDs des companies pour le providerId
        $companyIdsArray = $this->getCompanyIdsForProvider($providerId);

        if (empty($companyIdsArray)) {
            // Si aucun ID de compagnie n'est trouvé, retourner 0
            error_log('No company IDs found for provider: ' . $providerId);
            return 0;
        }

        // Compter les réservations actives pour les IDs des companies récupérées
        $query = $this->_em->createQuery('
        SELECT COUNT(b.id)
        FROM App\Entity\Booking b
        JOIN b.company c
        WHERE c.id IN (:companyIds)
        AND b.status = :status
    ')
            ->setParameter('companyIds', $companyIdsArray)
            ->setParameter('status', 'pending');

        try {
            $activeReservationCount = $query->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $activeReservationCount = 0;
        }

        return (int) $activeReservationCount;
    }

    public function getCancelledReservations(string $providerId): int
    {
        // Récupérer les IDs des companies pour le providerId
        $companyIdsArray = $this->getCompanyIdsForProvider($providerId);

        if (empty($companyIdsArray)) {
            // Si aucun ID de compagnie n'est trouvé, retourner 0
            error_log('No company IDs found for provider: ' . $providerId);
            return 0;
        }

        // Compter les réservations actives pour les IDs des companies récupérées
        $query = $this->_em->createQuery('
        SELECT COUNT(b.id)
        FROM App\Entity\Booking b
        JOIN b.company c
        WHERE c.id IN (:companyIds)
        AND b.status = :status
    ')
            ->setParameter('companyIds', $companyIdsArray)
            ->setParameter('status', 'cancelled');

        try {
            $activeReservationCount = $query->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $activeReservationCount = 0;
        }

        return (int) $activeReservationCount;
    }

    public function getDistinctServices(string $providerId): int
    {
        $companyIdsArray = $this->getCompanyIdsForProvider($providerId);

        if (empty($companyIdsArray)) {
            // Si aucun ID de compagnie n'est trouvé, retourner 0
            error_log('No company IDs found for provider: ' . $providerId);
            return 0;
        }

        // Requête pour récupérer les services distincts offerts par la compagnie
        $query = $this->_em->createQuery('
        SELECT COUNT(DISTINCT cs.service)
        FROM App\Entity\CompanyService cs
        WHERE cs.company IN (:companyIds)
    ')
            ->setParameter('companyIds', $companyIdsArray);

        try {
            $distinctServicesCount = $query->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $distinctServicesCount = 0;
        }

        // Log du nombre de services distincts
        error_log('Distinct services count for provider ' . $providerId . ': ' . $distinctServicesCount);

        return (int) $distinctServicesCount;
    }
}
