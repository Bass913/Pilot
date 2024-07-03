<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\UnavailabilityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UnavailabilityRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/api/unavailabilities/{id}',
            security: "is_granted('UNAVAILABILITY_READ', object)"
        ),
        new GetCollection(
            uriTemplate: '/api/unavailabilities',
            security: "is_granted('ROLE_SUPERADMIN')"
        ),
        new Post(
            uriTemplate: '/api/unavailabilities',
            denormalizationContext: ['groups' => ['add-unavailability']],
            #securityPostDenormalize: "is_granted('UNAVAILABILITY_CREATE',object)"
        ),
        new Patch(
            uriTemplate: '/api/unavailabilities/{id}',
            denormalizationContext: ['groups' => ['update-unavailability']],
            securityPostDenormalize: "is_granted('UNAVAILABILITY_EDIT', object)"
        ),
        new Delete(
            uriTemplate: '/api/unavailabilities/{id}',
            security: "is_granted('UNAVAILABILITY_DELETE', object)"
        )
    ],
    normalizationContext: ['groups' => ['read-unavailability']]
)
]
class Unavailability
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private ?Uuid $id = null;

    #[Groups(['read-company-details', 'user:read:planning','user:employee:read:planning', 'read-company-planning', 'add-company', 'read-unavailability', 'add-unavailability', 'update-unavailability'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[Groups(['read-company-details', 'user:read:planning','user:employee:read:planning', 'read-company-planning', 'add-company', 'read-unavailability', 'add-unavailability', 'update-unavailability'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[Groups(['add-unavailability'])]
    #[ORM\ManyToOne(inversedBy: 'unavailabilities')]
    private ?User $user = null;

    #[Groups(['add-unavailability'])]

    #[ORM\ManyToOne(inversedBy: 'unavailabilities')]
    private ?Company $company = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

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
