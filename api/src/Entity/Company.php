<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Filter\CompanySearch;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;



#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['read-company-details']]),
        new GetCollection(normalizationContext: ['groups' => ['read-company']]),
        new Get(
            uriTemplate: '/companies/{id}/planning',
            normalizationContext: ['groups' => ['read-company-planning']]
        ),
        new Post(
            uriTemplate: '/api/companies',
            denormalizationContext: ['groups' => ['add-company']],
            securityPostDenormalize: "is_granted('COMPANY_CREATE', object)",
            securityPostDenormalizeMessage: "Vous n'avez pas les droits requis pour ajouter un établissement"
        ),
        new Patch(
            uriTemplate: '/api/companies/{id}',
            denormalizationContext: ['groups' => ['update-company']],
            securityPostDenormalize: "is_granted('COMPANY_EDIT', object)",
            securityPostDenormalizeMessage: "Vous n'avez pas les droits requis pour modifier cet établissement"
        ),
        new Delete(
            uriTemplate: '/api/companies/{id}',
            security: "is_granted('ROLE_SUPERADMIN') or (is_granted('ROLE_ADMIN') and user.getCompanies().contains(object))",
            securityMessage: "Vous n'avez pas les droits requis pour supprimer cet établissement"
        )
    ],
    normalizationContext: ['groups' => ['read-company']]
)]
#[ApiFilter(CompanySearch::class)]
class Company
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['user:create', 'user:read:login'])]
    private ?Uuid $id = null;

    #[Groups(['read-company-details', 'read-company', 'read-booking','user:client:read:booking','user:employee:read:booking', 'user:read:company', 'add-company','update-company' ])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;
    #[Groups(['read-company-details', 'read-company', 'read-booking','user:client:read:booking', 'user:read:company', 'add-company', 'update-company'])]
    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[Groups(['read-company-details', 'add-company', 'update-company'])]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[Groups(['read-company-details', 'read-company', 'read-booking','user:client:read:booking', 'user:read:company', 'add-company', 'update-company'])]
    #[ORM\Column(length: 10)]
    private ?string $zipcode = null;

    #[Groups(['read-company-details', 'read-company', 'read-booking','user:client:read:booking', 'user:read:company', 'add-company', 'update-company'])]
    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[Groups(['read-company-details', 'read-company', 'user:read:company', 'add-company', 'update-company'])]
    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale: 8, nullable: true)]
    private ?float $longitude = null;

    #[Groups(['read-company-details', 'read-company', 'user:read:company', 'add-company', 'update-company'])]
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 8, nullable: true)]
    private ?float $latitude = null;

    #[Groups(['read-company-details', 'add-company'])]
    #[ORM\OneToMany(mappedBy: 'company', targetEntity: CompanyService::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $companyServices;

    #[Groups(['read-company-details'])]
    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Review::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $reviews;

    #[Groups(['read-company-details', 'read-company', 'user:read:company'])]
    #[ORM\Column(nullable: true)]
    private ?float $reviewRating = null;

    #[Groups(['read-company-details', 'read-company-planning', 'add-company'])]
    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Unavailability::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $unavailabilities;

    #[Groups(['read-company-details', 'read-company-planning', 'add-company'])]
    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Schedule::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $schedules;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: User::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $users;

    #[Groups(['read-company-details', 'read-company', 'user:read:company', 'add-company'])]
    #[ORM\ManyToOne(inversedBy: 'companies')]
    private ?Speciality $speciality;

    #[Groups(['read-company-details', 'read-company', 'user:read:company'])]
    #[ORM\Column(nullable: true)]
    private ?int $reviewCount = null;

    #[Groups(['read-company-details', 'read-company', 'user:read:company'])]
    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $images = null;

    #[ORM\ManyToOne(inversedBy: 'companies')]
    private ?User $user = null;

    #[Groups(['read-company-planning'])]

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Booking::class)]
    private Collection $bookings;


    public function __construct()
    {
        $this->companyServices = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->unavailabilities = new ArrayCollection();
        $this->schedules = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->bookings = new ArrayCollection();
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

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return Collection<int, CompanyService>
     */
    public function getCompanyServices(): Collection
    {
        return $this->companyServices;
    }

    public function addCompanyService(CompanyService $companyService): static
    {
        if (!$this->companyServices->contains($companyService)) {
            $this->companyServices->add($companyService);
            $companyService->setCompany($this);
        }

        return $this;
    }

    public function removeCompanyService(CompanyService $companyService): static
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

    public function getReviewCount(): ?int
    {
        return $this->reviewCount;
    }

    public function setReviewCount(?int $reviewCount): static
    {
        $this->reviewCount = $reviewCount;

        return $this;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): static
    {
        $this->images = $images;

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

    public function setFile(?File $file = null): void
    {
        $this->file = $file;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setCompany($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getCompany() === $this) {
                $booking->setCompany(null);
            }
        }

        return $this;
    }
}
