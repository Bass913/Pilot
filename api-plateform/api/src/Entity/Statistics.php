<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Repository\StatisticsRepository;
use App\Controller\StatisticsAdminController;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatisticsRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/stats/admin',
            controller: StatisticsAdminController::class,
            read: false
        )
    ]
)]
class Statistics
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $totalReservations = null;

    #[ORM\Column]
    private ?int $totalClients = null;

    #[ORM\Column]
    private ?int $todaysReservations = null;

    #[ORM\Column]
    private ?int $weeklyReservations = null;

    #[ORM\Column]
    private ?int $monthlyReservations = null;

    #[ORM\Column]
    private ?int $activeReservations = null;


    #[ORM\Column]
    private ?int $cancelledReservations = null;

    #[ORM\Column]
    private ?int $distinctClientsPerWeek = null;

    #[ORM\Column]
    private ?array $totalServicesPerCompany = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalReservations(): ?int
    {
        return $this->totalReservations;
    }


    public function setTotalReservations(int $totalReservations): static
    {
        $this->totalReservations = $totalReservations;

        return $this;
    }

    public function getTotalClients(): ?int
    {
        return $this->totalClients;
    }

    public function setTotalClients(int $totalClients): static
    {
        $this->totalClients = $totalClients;

        return $this;
    }

    public function getTodaysReservations(): ?int
    {
        return $this->todaysReservations;
    }

    public function setTodaysReservations(int $todaysReservations): static
    {
        $this->todaysReservations = $todaysReservations;

        return $this;
    }

    public function getWeeklyReservations(): ?int
    {
        return $this->weeklyReservations;
    }

    public function setWeeklyReservations(int $weeklyReservations): static
    {
        $this->weeklyReservations = $weeklyReservations;

        return $this;
    }

    public function getMonthlyReservations(): ?int
    {
        return $this->monthlyReservations;
    }

    public function setMonthlyReservations(int $monthlyReservations): static
    {
        $this->monthlyReservations = $monthlyReservations;

        return $this;
    }

    public function getTotalActiveReservations(): ?int
    {
        return $this->activeReservations;
    }

    public function setTotalActiveReservations(int $activeReservations): static
    {
        $this->activeReservations = $activeReservations;

        return $this;
    }


    public function getTotalCancelledReservations(): ?int
    {
        return $this->cancelledReservations;
    }

    public function setTotalCancelledReservations(int $cancelledReservations): static
    {
        $this->cancelledReservations = $cancelledReservations;

        return $this;
    }

    public function getDistinctClientsWithReservationsPerWeek(): ?int
    {
        return $this->distinctClientsPerWeek;
    }

    public function setDistinctClientsWithReservationsPerWeek(int $distinctClientsPerWeek): static
    {
        $this->distinctClientsPerWeek = $distinctClientsPerWeek;

        return $this;
    }

    public function getTotalServicesPerCompany(): ?array
    {
        return $this->totalServicesPerCompany;
    }

    public function setTotalServicesPerCompany(array $totalServicesPerCompany): static
    {
        $this->totalServicesPerCompany = $totalServicesPerCompany;

        return $this;
    }
}
