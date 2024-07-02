<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Link;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/api/bookings',
            normalizationContext: ['groups' => ['read-booking']],
            security: "is_granted('ROLE_SUPERADMIN') or is_granted('ROLE_ADMIN')",
            filters: ['booking.search']
        ),
        new GetCollection(
            uriTemplate: '/api/companies/{id}/bookings',
            uriVariables: [
                'id' => new Link(fromClass: Company::class, fromProperty: 'bookings')
            ],
            normalizationContext: ['groups' => ['read-booking']],
            security: "is_granted('ROLE_SUPERADMIN') or is_granted('ROLE_ADMIN')",
            securityMessage: "vous n'avez pas les droits pour visualiser les RDV de cette entreprise"
        ),
        new Get(
            uriTemplate: '/api/bookings/{id}',
            security: "(is_granted('ROLE_ADMIN') and object.getCompany() == user.getCompany()) or object.getClient() == user or object.getEmployee() == user",
            securityMessage: "Ce RDV ne vous appartient pas"
        ),
        new Patch(
            uriTemplate: '/api/bookings/{id}',
            securityPostDenormalize: "is_granted('BOOKING_EDIT', object)",
            securityPostDenormalizeMessage:"Vous essayez de décaler un RDV pour un autre client"

        ),
        new Post(
            uriTemplate: '/api/bookings',
            securityPostDenormalize: "is_granted('BOOKING_CREATE', object)",
            securityPostDenormalizeMessage:"Vous essayez de prendre RDV pour un autre client"
        ),
        new Delete(
            uriTemplate: '/api/bookings/{id}',
            security: "is_granted('BOOKING_DELETE', object) or (is_granted('ROLE_ADMIN') and object.getCompany() == user.getCompany())",
            securityMessage: "Vous n'avez pas le droit d'annuler ce RDV"
        )
    ],
    normalizationContext: ['groups' => ['read-booking']],
    order: ['startDate' => 'DESC']
)]
class Booking
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[Groups(['read-booking', 'user:read:planning','read-company-planning', 'user:client:read:booking', 'user:employee:read:booking'])]
    #[ORM\Column(length: 255)]
    private ?string $startDate = null;


    #[Groups(['read-booking', 'user:read:planning', 'user:client:read:booking', 'user:employee:read:booking'])]
    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[Groups(['read-booking', 'user:client:read:booking', 'user:employee:read:booking'])]
    #[ORM\ManyToOne(inversedBy: 'bookings')]
    private ?CompanyService $companyService = null;

    #[Groups(['read-booking'])]
    #[ORM\ManyToOne(inversedBy: 'clientBookings')]
    private ?User $client = null;

    #[Groups(['read-booking'])]
    #[ORM\ManyToOne(inversedBy: 'employeeBookings')]
    private ?User $employee = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

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

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }
}
