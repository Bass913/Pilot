<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\SpecialityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SpecialityRepository::class)]
#[ApiResource
(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            uriTemplate: '/api/specialities',
            denormalizationContext: ['groups' => ['add-speciality']],
            securityPostDenormalize: "is_granted('ROLE_SUPERADMIN')"

        ),
        new Patch(
            uriTemplate: '/api/specialities/{id}',
            denormalizationContext: ['groups' => ['update-speciality']],
            securityPostDenormalize: "is_granted('ROLE_SUPERADMIN')"
        ),
        new Delete(
            uriTemplate: '/api/specialities/{id}',
            security: "is_granted('ROLE_SUPERADMIN')"
        )
    ],
)
]
class Speciality
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private ?Uuid $id = null;

    #[Groups(['read-company-details', 'read-company', 'user:read:company', 'add-speciality', 'update-speciality'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'speciality', targetEntity: Company::class)]
    private Collection $companies;

    #[ORM\OneToMany(mappedBy: 'speciality', targetEntity: Service::class)]
    private Collection $services;

    public function __construct()
    {
        $this->companies = new ArrayCollection();
        $this->services = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Company>
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(Company $company): static
    {
        if (!$this->companies->contains($company)) {
            $this->companies->add($company);
            $company->setSpeciality($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): static
    {
        if ($this->companies->removeElement($company)) {
            // set the owning side to null (unless already changed)
            if ($company->getSpeciality() === $this) {
                $company->setSpeciality(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setSpeciality($this);
        }

        return $this;
    }

    public function removeService(Service $service): static
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getSpeciality() === $this) {
                $service->setSpeciality(null);
            }
        }

        return $this;
    }
}
