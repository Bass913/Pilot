<?php

namespace App\Entity;

use App\Repository\RequestRepository;
use ApiPlatform\Metadata\ApiResource;
use App\State\RequestStateProcessor;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
#[Vich\Uploadable]
#[ApiResource(
    processor: RequestStateProcessor::class,
    normalizationContext: ['groups' => ['request:read']],
    denormalizationContext: ['groups' => ['request:write']],
    operations: [
        new GetCollection(),
        new Post(
            inputFormats: ['multipart' => ['multipart/form-data']]
        )
    ]
)]
class Request
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['request:read', 'request:write'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['request:read', 'request:write'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['request:read', 'request:write'])]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    #[Groups(['request:read', 'request:write'])]
    private ?string $email = null;

    #[Groups(['request:write'])]
    #[Vich\UploadableField(mapping: 'kbis_upload', fileNameProperty: 'kbis')]
    private ?File $file = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['request:read'])]
    private ?string $kbis = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;


    public function getId(): ?int
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

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
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setFile(?File $file = null): void
    {
        $this->file = $file;
        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setKbis(?string $kbis): void
    {
        $this->kbis = $kbis;
    }

    public function getKbis(): ?string
    {
        return $this->kbis;
    }
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
