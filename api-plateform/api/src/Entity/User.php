<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['user:read']]),
        new GetCollection(normalizationContext: ['groups' => ['user:read']]),
        new Get(
            uriTemplate: '/users/{id}/bookings',
            normalizationContext: ['groups' => ['user:read:booking']]
        ),
        new Get(
            uriTemplate: '/me',
            provider: User::class,
            normalizationContext: ['groups' => ['user:read']],
            security: "is_granted('ROLE_USER')"
        ),
        new GetCollection(
            uriTemplate: '/companies/{id}/employees',
            uriVariables: [
                'id' => new Link(fromProperty: 'users', fromClass: Company::class)
            ],
        ),
        new GetCollection(
            uriTemplate: '/companies/{id}/employees/planning',
            uriVariables: [
                'id' => new Link(fromProperty: 'users', fromClass: Company::class)
            ],
            normalizationContext: ['groups' => ['user:read:planning']]
        ),
        new Post(
            uriTemplate: '/register',
            denormalizationContext: ['groups' => ['user:register']],
            validationContext: ['groups' => ['user:register']]
        ),
        new Post(
            uriTemplate: '/admin/users',
            denormalizationContext: ['groups' => ['user:create']],
            validationContext: ['groups' => ['user:create']]
        ),
        new Patch(
            denormalizationContext: ['groups' => ['user:update']]
        )
    ],
    normalizationContext: ['groups' => ['user:read']]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['user:read'])]
    private ?Uuid $id;

    #[ORM\Column(length: 50)]
    #[Groups(['user:register', 'user:read', 'user:create', 'user:update', 'read-company-details', 'user:read:planning', 'read-review', 'read-booking'])]
    #[Assert\NotBlank(groups: ['user:register', 'user:create'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    #[Groups(['user:register', 'user:read', 'user:create', 'user:update', 'read-company-details',  'user:read:planning', 'read-review', 'read-booking'])]
    #[Assert\NotBlank(groups: ['user:register', 'user:create'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user:register', 'user:read', 'user:create'])]
    #[Assert\Email(groups: ['user:register', 'user:create'])]
    #[Assert\NotBlank(groups: ['user:register', 'user:create'])]
    private ?string $email = null;

    /**
     * @var string[]
     */
    #[ORM\Column]
    #[Groups(['user:read'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['user:register', 'user:create'])]
    #[Assert\NotBlank(groups: ['user:register', 'user:create'])]
    #[Assert\Length(min: 8, groups: ['user:register', 'user:create'])]
    private ?string $password = null;

    #[Groups(['read-company-details', 'user:read:planning'])]
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Unavailability::class)]
    private Collection $unavailabilities;

    #[Groups(['read-company-details', 'user:read:planning'])]
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Schedule::class)]
    private Collection $schedules;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Company $company = null;

    #[Groups(['read-company-details', 'user:read:planning', 'user:read:booking'])]
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Booking::class)]
    private Collection $clientBookings;

    #[Groups(['read-company-details'])]
    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: Booking::class)]
    private Collection $employeeBookings;

    #[Groups(['user:register', 'user:create', 'user:read'])]
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: Company::class)]
    private Collection $companies;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Review::class)]
    private Collection $reviews;


    public function __construct()
    {
        $this->unavailabilities = new ArrayCollection();
        $this->schedules = new ArrayCollection();
        $this->clientBookings = new ArrayCollection();
        $this->employeeBookings = new ArrayCollection();
        $this->companies = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    #[Groups(['admin:read'])]
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    #[Groups(['admin:create'])]
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $unavailability->setUser($this);
        }

        return $this;
    }

    public function removeUnavailability(Unavailability $unavailability): static
    {
        if ($this->unavailabilities->removeElement($unavailability)) {
            // set the owning side to null (unless already changed)
            if ($unavailability->getUser() === $this) {
                $unavailability->setUser(null);
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
            $schedule->setUser($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): static
    {
        if ($this->schedules->removeElement($schedule)) {
            // set the owning side to null (unless already changed)
            if ($schedule->getUser() === $this) {
                $schedule->setUser(null);
            }
        }

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

    /**
     * @return Collection<int, Booking>
     */
    public function getClientBookings(): Collection
    {
        return $this->clientBookings;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getEmployeeBookings(): Collection
    {
        return $this->employeeBookings;
    }

    public function addClientBooking(Booking $booking): static
    {
        if (!$this->clientBookings->contains($booking)) {
            $this->clientBookings->add($booking);
            $booking->setClient($this);
        }

        return $this;
    }

    public function addEmployeeBooking(Booking $booking): static
    {
        if (!$this->employeeBookings->contains($booking)) {
            $this->employeeBookings->add($booking);
            $booking->setClient($this);
        }

        return $this;
    }

    public function removeClientBooking(Booking $booking): static
    {
        if ($this->clientBookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getClient() === $this) {
                $booking->setClient(null);
            }
        }

        return $this;
    }

    public function removeEmployeeBooking(Booking $booking): static
    {
        if ($this->employeeBookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getClient() === $this) {
                $booking->setClient(null);
            }
        }

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

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
            $company->setUser($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): static
    {
        if ($this->companies->removeElement($company)) {
            // set the owning side to null (unless already changed)
            if ($company->getUser() === $this) {
                $company->setUser(null);
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
            $review->setClient($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getClient() === $this) {
                $review->setClient(null);
            }
        }

        return $this;
    }
}
