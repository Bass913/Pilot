<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\ScheduleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
#[ApiResource
(
    operations: [
        new Get(
            uriTemplate: '/api/schedules/{id}',
            security: "is_granted('SCHEDULE_READ', object)"
        ),
        new GetCollection(
            uriTemplate: '/api/schedules',
            security: "is_granted('ROLE_SUPERADMIN')"
        ),
        new Post(
            uriTemplate: '/api/schedules',
            denormalizationContext: ['groups' => ['add-schedule']],
            securityPostDenormalize: "is_granted('SCHEDULE_CREATE',object)"
        ),
        new Patch(
            uriTemplate: '/api/schedules/{id}',
            denormalizationContext: ['groups' => ['update-schedule']],
            securityPostDenormalize: "is_granted('SCHEDULE_EDIT', object)"
        ),
        new Delete(
            uriTemplate: '/api/schedules/{id}',
            security: "is_granted('SCHEDULE_DELETE', object)"
        )
    ],
    normalizationContext: ['groups' => ['read-schedule']]
)
]
class Schedule
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[Groups(['read-company-details', 'user:read:planning','user:employee:read:planning', 'read-company-planning', 'add-company', 'read-schedule', 'add-schedule'])]
    #[ORM\Column(length: 9)]
    private ?string $dayOfWeek = null;

    #[Groups(['read-company-details', 'user:read:planning','user:employee:read:planning', 'read-company-planning', 'add-company', 'read-schedule', 'add-schedule', 'update-schedule'])]
    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startTime = null;

    #[Groups(['read-company-details', 'user:read:planning','user:employee:read:planning', 'add-company', 'read-schedule', 'add-schedule', 'update-schedule'])]
    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endTime = null;

    #[Groups(['read-schedule', 'add-schedule'])]
    #[ORM\ManyToOne(inversedBy: 'schedules')]
    private ?Company $company = null;

    #[Groups(['read-schedule', 'add-schedule'])]
    #[ORM\ManyToOne(inversedBy: 'schedules')]
    private ?User $user = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getDayOfWeek(): ?string
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(string $dayOfWeek): static
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(?\DateTimeInterface $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(?\DateTimeInterface $endTime): static
    {
        $this->endTime = $endTime;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
