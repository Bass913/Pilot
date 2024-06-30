<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\StatisticsAdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class StatisticsAdminController extends AbstractController
{
    private $statisticsRepository;
    private Security $security;

    public function __construct(StatisticsAdminRepository $statisticsRepository, Security $security)
    {
        $this->statisticsRepository = $statisticsRepository;
        $this->security = $security;
    }


    public function __invoke(string $id): JsonResponse
    {
        /*$user = $this->security->getUser();
        assert($user instanceof User);
        if($user->getCompany()->getId() !== $id){
        }*/
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
