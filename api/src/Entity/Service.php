<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\ServicesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ServicesRepository::class)]
#[ApiResource
(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            uriTemplate: '/api/services',
            denormalizationContext: ['groups' => ['add-service']],
            securityPostDenormalize: "is_granted('ROLE_SUPERADMIN')"

        ),
        new Patch(
            uriTemplate: '/api/services/{id}',
            denormalizationContext: ['groups' => ['update-service']],
            securityPostDenormalize: "is_granted('ROLE_SUPERADMIN')"
        ),
        new Delete(
            uriTemplate: '/api/services/{id}',
            security: "is_granted('ROLE_SUPERADMIN')"
        )
    ],
)
]
class Service
{

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[Groups(['read-company-details', 'read-company-service', 'read-booking','user:client:read:booking', 'user:employee:read:booking', 'add-service', 'update-service'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['add-service'])]
    #[ORM\ManyToOne(inversedBy: 'services')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Speciality $speciality = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSpeciality(): ?Speciality
    {
        return $this->speciality;
    }

    public function setSpeciality(?Speciality $speciality): static
    {
        $this->speciality = $speciality;

        return $this;
    }
}
