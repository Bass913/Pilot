<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read-company']]
)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['read-company'])]

    #[ORM\Column(length: 255)]
    private ?string $name = null;
    #[Groups(['read-company'])]
    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[Groups(['read-company'])]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[Groups(['read-company'])]
    #[ORM\Column(length: 10)]
    private ?string $zipcode = null;

    #[Groups(['read-company'])]
    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $kbis = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[Groups(['read-company'])]
    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale: 8, nullable: true)]
    private ?string $longitude = null;

    #[Groups(['read-company'])]
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 8, nullable: true)]
    private ?string $latitude = null;

    #[Groups(['read-company'])]
    #[ORM\OneToMany(mappedBy: 'company', targetEntity: CompanyServices::class)]
    private Collection $companyServices;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: ImgCompany::class)]
    private Collection $imgCompany;

    #[Groups(['read-company'])]
    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Review::class)]
    private Collection $reviews;

    #[Groups(['read-company'])]
    #[ORM\Column(nullable: true)]
    private ?float $reviewRating = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Unavailability::class)]
    private Collection $unavailabilities;

    #[Groups(['read-company'])]
    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Schedule::class)]
    private Collection $schedules;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: User::class)]
    private Collection $users;

    #[ORM\ManyToOne(inversedBy: 'companies')]
    private ?Speciality $speciality;


    public function __construct()
    {
        $this->companyServices = new ArrayCollection();
        $this->imgCompany = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->unavailabilities = new ArrayCollection();
        $this->schedules = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getKbis(): ?string
    {
        return $this->kbis;
    }

    public function setKbis(string $kbis): static
    {
        $this->kbis = $kbis;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return Collection<int, CompanyServices>
     */
    public function getCompanyServices(): Collection
    {
        return $this->companyServices;
    }

    public function addCompanyService(CompanyServices $companyService): static
    {
        if (!$this->companyServices->contains($companyService)) {
            $this->companyServices->add($companyService);
            $companyService->setCompany($this);
        }

        return $this;
    }

    public function removeCompanyService(CompanyServices $companyService): static
    {
        if ($this->companyServices->removeElement($companyService)) {
            // set the owning side to null (unless already changed)
            if ($companyService->getCompany() === $this) {
                $companyService->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ImgCompany>
     */
    public function getImgCompany(): Collection
    {
        return $this->imgCompany;
    }

    public function addImgCompany(ImgCompany $imgCompany): static
    {
        if (!$this->imgCompany->contains($imgCompany)) {
            $this->imgCompany->add($imgCompany);
            $imgCompany->setCompany($this);
        }

        return $this;
    }

    public function removeImgCompany(ImgCompany $imgCompany): static
    {
        if ($this->imgCompany->removeElement($imgCompany)) {
            // set the owning side to null (unless already changed)
            if ($imgCompany->getCompany() === $this) {
                $imgCompany->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setCompany($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getCompany() === $this) {
                $review->setCompany(null);
            }
        }

        return $this;
    }

    public function getReviewRating(): ?float
    {
        return $this->reviewRating;
    }

    public function setReviewRating(?float $reviewRating): static
    {
        $this->reviewRating = $reviewRating;

        return $this;
    }

    /**
     * @return Collection<int, Unavailability>
     */
    public function getUnavailabilities(): Collection
    {
        return $this->unavailabilities;
    }

    public function addUnavailability(Unavailability $unavailability): static
    {
        if (!$this->unavailabilities->contains($unavailability)) {
            $this->unavailabilities->add($unavailability);
            $unavailability->setCompany($this);
        }

        return $this;
    }

    public function removeUnavailability(Unavailability $unavailability): static
    {
        if ($this->unavailabilities->removeElement($unavailability)) {
            // set the owning side to null (unless already changed)
            if ($unavailability->getCompany() === $this) {
                $unavailability->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Schedule>
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function addSchedule(Schedule $schedule): static
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules->add($schedule);
            $schedule->setCompany($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): static
    {
        if ($this->schedules->removeElement($schedule)) {
            // set the owning side to null (unless already changed)
            if ($schedule->getCompany() === $this) {
                $schedule->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setCompany($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCompany() === $this) {
                $user->setCompany(null);
            }
        }

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
