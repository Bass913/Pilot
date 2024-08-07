<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Dto\UserInput;
use App\Repository\UserRepository;
use App\State\UserProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['user:read']]),
        new GetCollection(
            uriTemplate: '/api/users',
            normalizationContext: ['groups' => ['user:read']],
            security: "is_granted('ROLE_SUPERADMIN')",
            securityMessage: "Vous n'êtes pas super admin"
        ),
        new GetCollection(
            uriTemplate: '/api/users/employees',
            normalizationContext: ['groups' => ['user:read']],
            security: "is_granted('ROLE_SUPERADMIN')",
            securityMessage: "Vous n'êtes pas super admin"
        ),
        new Get(
            uriTemplate: '/api/client/{id}/bookings',
            normalizationContext: ['groups' => ['user:client:read:booking']],
            security: "object.getId() == user.getId()",
            securityMessage: "Ce compte ne vous appartient pas"
        ),
        new Get(
            uriTemplate: '/api/users/{id}/planning',
            normalizationContext: ['groups' => ['user:read:planning']],
            security: "object.getId() == user.getId()",
            securityMessage: "Ce compte ne vous appartient pas"
        ),
        new Get(
            uriTemplate: '/api/employee/{id}/bookings',
            normalizationContext: ['groups' => ['user:employee:read:booking']],
            security: "object.getId() == user.getId()",
            securityMessage: "Ce compte ne vous appartient pas"
        ),
        new Get(
            uriTemplate: '/api/me',
            normalizationContext: ['groups' => ['user:read:login']],
        ),
        new Get(
            uriTemplate: '/api/users/{id}/companies',
            normalizationContext: ['groups' => ['user:read:company']],
            security: "is_granted('ROLE_SUPERADMIN') or (is_granted('ROLE_ADMIN') and object.getId() == user.getId()) "
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
            normalizationContext: ['groups' => ['user:employee:read:planning']]
        ),
        new Post(
            uriTemplate: '/register',
            denormalizationContext: ['groups' => ['user:register']],
            validationContext: ['groups' => ['user:register']]
        ),
        new Post(
            uriTemplate: '/api/users',
            denormalizationContext: ['groups' => ['user:create']],
            securityPostDenormalize: "is_granted('USER_CREATE_EMPLOYEE', object)",
            securityPostDenormalizeMessage:"Vous n'avez pas les droits requis pour créer un employée pour cet entreprise",
            validationContext: ['groups' => ['user:create']],
            input: UserInput::class
        ),
        new Patch(
            uriTemplate: '/api/users/{id}',
            denormalizationContext: ['groups' => ['user:update']],
            securityPostDenormalize: "is_granted('USER_EDIT', object) ",
            securityPostDenormalizeMessage: "Vous n'avez pas les droits requis",
            validationContext: ['groups' => ['user:update']]
        ),
        new Patch(
            uriTemplate: '/api/users/password/{id}',
            denormalizationContext: ['groups' => ['user:update:password']],
            securityPostDenormalize: "is_granted('USER_EDIT_PASSWORD', object)",
            securityPostDenormalizeMessage: "Vous n'êtes pas le propriétaire de ce compte",
            validationContext: ['groups' => ['user:upate:password']],

        ),
        new Delete(
            uriTemplate: '/api/users/{id}',
            security: "is_granted('USER_DELETE', object)",
            securityMessage: "Vous n'êtes pas le propriétaire de ce compte"

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
    #[Groups(['user:read','user:read:login' ])]
    private ?Uuid $id;

    #[ORM\Column(length: 50)]
    #[Groups(['user:register', 'user:read', 'user:create', 'user:update', 'user:employee:read:planning', 'read-review', 'read-booking', 'user:read:login', 'read-schedule'])]
    #[Assert\NotBlank(groups: ['user:register', 'user:create', 'user:update'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    #[Groups(['user:register', 'user:read', 'user:create', 'user:update',  'user:employee:read:planning', 'read-review', 'read-booking', 'user:read:login', 'read-schedule'])]
    #[Assert\NotBlank(groups: ['user:register', 'user:create', 'user:update'])]
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
    #[Groups(['user:read', 'user:read:login'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['user:register', 'user:create','user:update:password'])]
    #[Assert\NotBlank(groups: ['user:register', 'user:create', 'user:update:password'])]
    #[Assert\Length(min: 8, groups: ['user:register', 'user:create','user:update:password'])]
    private ?string $password = null;

    #[Groups(['user:read:planning', 'user:employee:read:planning'])]
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Unavailability::class, cascade: ['remove'],orphanRemoval: true)]
    private Collection $unavailabilities;

    #[Groups(['user:read:planning', 'user:employee:read:planning'])]
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Schedule::class,cascade: ['remove'],orphanRemoval: true)]
    private Collection $schedules;

    #[Groups(['user:read:login', 'user:create'])]
    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Company $company = null;

    #[Groups([ 'user:client:read:booking'])]
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Booking::class, cascade: ['remove'],orphanRemoval: true)]
    private Collection $clientBookings;

    #[Groups(['user:employee:read:booking',  'user:employee:read:planning', 'user:read:planning'])]
    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: Booking::class, cascade: ['remove'],orphanRemoval: true)]
    private Collection $employeeBookings;

    #[Groups(['user:register', 'user:create', 'user:read'])]
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[Groups(['user:read:login', 'user:read:company'])]
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Company::class)]
    private Collection $companies;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Review::class, cascade: ['remove'],orphanRemoval: true)]
    private Collection $reviews;

    #[ORM\Column]
    private ?bool $active = false;

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

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }
}
