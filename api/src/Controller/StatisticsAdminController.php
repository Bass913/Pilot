<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\StatisticsAdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
        $user = $this->security->getUser();
        assert($user instanceof User);
        if(($this->security->isGranted('ROLE_ADMIN') && !$this->security->isGranted('ROLE_SUPERADMIN')) && $user->getId() != $id){
            throw new AccessDeniedException("vous n'avez pas les droits pour visualiser les statistiques de cet admin");

        }

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
