<?php

namespace App\Controller;

use App\Repository\StatisticsAdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class StatisticsAdminController extends AbstractController
{
    private $statisticsRepository;

    public function __construct(StatisticsAdminRepository $statisticsRepository)
    {
        $this->statisticsRepository = $statisticsRepository;
    }


    public function __invoke(string $id): JsonResponse
    {
        $statistics = $this->statisticsRepository->getProviderStatistics($id);

        return new JsonResponse([
            'totalReservations' => $statistics['totalReservations'],
            'totalEmployees' => $statistics['totalEmployees'],
            'todaysReservations' => $statistics['todaysReservations'],
            'weeklyReservations' => $statistics['weeklyReservations'],
            'monthlyReservations' => $statistics['monthlyReservations'],
            'activeReservations' => $statistics['activeReservations'],
            'cancelledReservations' => $statistics['cancelledReservations'],
            'distinctServices' => $statistics['distinctServices']
        ]);
    }
}