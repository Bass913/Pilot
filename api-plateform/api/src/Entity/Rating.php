<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/reviews/{id}/ratings',
            uriVariables: [
                'id' => new Link(fromProperty: 'ratings', fromClass: Review::class)
            ],
        ),
        new Get(),
        new Post(),
        new Put(),
        new Patch(),
        new Delete()
    ],
    normalizationContext: ['groups' => ['read-rating']]
)]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Review $review = null;

    #[Groups(['read-company-details', 'read-review', 'read-rating'])]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?CategoryReview $category = null;

    #[Groups(['read-company-details', 'read-review'])]
    #[ORM\Column]
    private ?int $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReview(): ?Review
    {
        return $this->review;
    }

    public function setReview(?Review $review): static
    {
        $this->review = $review;

        return $this;
    }

    public function getCategory(): ?CategoryReview
    {
        return $this->category;
    }

    public function setCategory(?CategoryReview $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }
}
