<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Common\Filter\SearchFilterInterface;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            denormalizationContext: ['groups' => 'create-recipe'],
            validationContext: ['groups' => 'create-recipe']
        ),
        new Patch(denormalizationContext: ['groups' => 'update-recipe'])
    ],
    normalizationContext: ['groups' => ['all-client', 'read-recipe']]
)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ApiFilter(SearchFilter::class, strategy: SearchFilterInterface::STRATEGY_PARTIAL)]
    #[Assert\NotBlank(groups:['create-recipe'])]
    #[Groups(['all-client','read-recipe','create-recipe','update-recipe'])]
    #[ORM\Column(length: 255)]
    private ?string $title = null;


    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;
    #[Groups(['all-client','create-recipe'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;
    #[Groups(['all-client','create-recipe'])]
    #[ORM\Column]
    private ?int $duration = null;
    #[Groups(['all-client','create-recipe'])]
    #[ORM\Column(length: 255)]
    private ?string $difficulty = null;
    #[Groups(['all-client','create-recipe'])]
    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[Groups(['all-client'])]
    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Quantity::class)]
    private Collection $quantities;

    public function __construct()
    {
        $this->quantities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

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

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }


    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Quantity>
     */
    public function getQuantities(): Collection
    {
        return $this->quantities;
    }

    public function addQuantity(Quantity $quantity): static
    {
        if (!$this->quantities->contains($quantity)) {
            $this->quantities->add($quantity);
            $quantity->setRecipe($this);
        }

        return $this;
    }

    public function removeQuantity(Quantity $quantity): static
    {
        if ($this->quantities->removeElement($quantity)) {
            // set the owning side to null (unless already changed)
            if ($quantity->getRecipe() === $this) {
                $quantity->setRecipe(null);
            }
        }

        return $this;
    }
}
