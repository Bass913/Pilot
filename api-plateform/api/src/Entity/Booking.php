<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Link;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use ApiPlatform\Metadata\GetCollection;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read-booking']],
    order: ['startDate' => 'DESC'],
    operations: [
        new GetCollection(
            uriTemplate: '/bookings',
            normalizationContext: ['groups' => ['read-booking']],
            filters: ['booking.search']
        ),
        new GetCollection(
            uriTemplate: '/companies/{id}/bookings',
            uriVariables: [
                'id' => new Link(fromClass: CompanyService::class, fromProperty: 'company')
            ],
            normalizationContext: ['groups' => ['read-booking']],
            filters: ['booking.search']
        ),
        new Post(),
    ]
)]
class Booking
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[Groups(['read-booking', 'user:read:planning', 'user:read:booking'])]
    #[ORM\Column(length: 255)]
    private ?string $startDate = null;


    #[Groups(['read-booking', 'user:read:planning', 'user:read:booking'])]
    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[Groups(['read-booking', 'user:read:booking'])]
    #[ORM\ManyToOne(inversedBy: 'bookings')]
    private ?CompanyService $companyService = null;

    #[ORM\ManyToOne(inversedBy: 'clientBookings')]
    private ?User $client = null;

    #[Groups(['read-booking'])]
    #[ORM\ManyToOne(inversedBy: 'employeeBookings')]
    private ?User $employee = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    public function setStartDate(string $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCompanyService(): ?CompanyService
    {
        return $this->companyService;
    }

    public function setCompanyService(?CompanyService $companyService): static
    {
        $this->companyService = $companyService;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getEmployee(): ?User
    {
        return $this->employee;
    }

    public function setEmployee(?User $employee): static
    {
        $this->employee = $employee;

        return $this;
    }
}
