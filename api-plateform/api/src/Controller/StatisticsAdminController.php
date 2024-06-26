<?php

namespace App\Controller;

use App\Repository\StatisticsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class StatisticsAdminController extends AbstractController
{
    private $statisticsRepository;

    public function __construct(StatisticsRepository $statisticsRepository)
    {
        $this->statisticsRepository = $statisticsRepository;
    }

    public function __invoke(): JsonResponse
    {
        $statistics = $this->statisticsRepository->getStatistics();


        return new JsonResponse([
            'totalReservations' => $statistics->getTotalReservations(),
            'totalClients' => $statistics->getTotalClients(),
            'todaysReservations' => $statistics->getTodaysReservations(),
            'weeklyReservations' => $statistics->getWeeklyReservations(),
            'monthlyReservations' => $statistics->getMonthlyReservations(),
            'activeReservations' => $statistics->getTotalActiveReservations(),
            'cancelledReservations' => $statistics->getTotalCancelledReservations(),
            'distinctClientsPerWeek' => $statistics->getDistinctClientsWithReservationsPerWeek(),
            'totalServicesPerCompany' => $statistics->getTotalServicesPerCompany(),
        ]);
    }
}
