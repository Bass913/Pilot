<?php

namespace App\Entity;

use App\Repository\OpeningHoursRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OpeningHoursRepository::class)]
class OpeningHours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $mondayOpeningHour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $mondayClosingHour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $tuesdayOpeningHour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $tuesdayClosingHour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $wednesdayOpeningHour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $wednesdayClosingHour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $thursdayOpeningHour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $thursdayClosingHour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $fridayOpeningHour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $fridayClosingHour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $saturdayOpeningHour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $saturdayClosingHour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $sundayOpeningHour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $sundayClosingHour = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMondayOpeningHour(): ?\DateTimeInterface
    {
        return $this->mondayOpeningHour;
    }

    public function setMondayOpeningHour(\DateTimeInterface $mondayOpeningHour): static
    {
        $this->mondayOpeningHour = $mondayOpeningHour;

        return $this;
    }

    public function getMondayClosingHour(): ?\DateTimeInterface
    {
        return $this->mondayClosingHour;
    }

    public function setMondayClosingHour(\DateTimeInterface $mondayClosingHour): static
    {
        $this->mondayClosingHour = $mondayClosingHour;

        return $this;
    }

    public function getTuesdayOpeningHour(): ?\DateTimeInterface
    {
        return $this->tuesdayOpeningHour;
    }

    public function setTuesdayOpeningHour(\DateTimeInterface $tuesdayOpeningHour): static
    {
        $this->tuesdayOpeningHour = $tuesdayOpeningHour;

        return $this;
    }

    public function getTuesdayClosingHour(): ?\DateTimeInterface
    {
        return $this->tuesdayClosingHour;
    }

    public function setTuesdayClosingHour(\DateTimeInterface $tuesdayClosingHour): static
    {
        $this->tuesdayClosingHour = $tuesdayClosingHour;

        return $this;
    }

    public function getWednesdayOpeningHour(): ?\DateTimeInterface
    {
        return $this->wednesdayOpeningHour;
    }

    public function setWednesdayOpeningHour(\DateTimeInterface $wednesdayOpeningHour): static
    {
        $this->wednesdayOpeningHour = $wednesdayOpeningHour;

        return $this;
    }

    public function getWednesdayClosingHour(): ?\DateTimeInterface
    {
        return $this->wednesdayClosingHour;
    }

    public function setWednesdayClosingHour(\DateTimeInterface $wednesdayClosingHour): static
    {
        $this->wednesdayClosingHour = $wednesdayClosingHour;

        return $this;
    }

    public function getThursdayOpeningHour(): ?\DateTimeInterface
    {
        return $this->thursdayOpeningHour;
    }

    public function setThursdayOpeningHour(\DateTimeInterface $thursdayOpeningHour): static
    {
        $this->thursdayOpeningHour = $thursdayOpeningHour;

        return $this;
    }

    public function getThursdayClosingHour(): ?\DateTimeInterface
    {
        return $this->thursdayClosingHour;
    }

    public function setThursdayClosingHour(\DateTimeInterface $thursdayClosingHour): static
    {
        $this->thursdayClosingHour = $thursdayClosingHour;

        return $this;
    }

    public function getFridayOpeningHour(): ?\DateTimeInterface
    {
        return $this->fridayOpeningHour;
    }

    public function setFridayOpeningHour(\DateTimeInterface $fridayOpeningHour): static
    {
        $this->fridayOpeningHour = $fridayOpeningHour;

        return $this;
    }

    public function getFridayClosingHour(): ?\DateTimeInterface
    {
        return $this->fridayClosingHour;
    }

    public function setFridayClosingHour(\DateTimeInterface $fridayClosingHour): static
    {
        $this->fridayClosingHour = $fridayClosingHour;

        return $this;
    }

    public function getSaturdayOpeningHour(): ?\DateTimeInterface
    {
        return $this->saturdayOpeningHour;
    }

    public function setSaturdayOpeningHour(\DateTimeInterface $saturdayOpeningHour): static
    {
        $this->saturdayOpeningHour = $saturdayOpeningHour;

        return $this;
    }

    public function getSaturdayClosingHour(): ?\DateTimeInterface
    {
        return $this->saturdayClosingHour;
    }

    public function setSaturdayClosingHour(\DateTimeInterface $saturdayClosingHour): static
    {
        $this->saturdayClosingHour = $saturdayClosingHour;

        return $this;
    }

    public function getSundayOpeningHour(): ?\DateTimeInterface
    {
        return $this->sundayOpeningHour;
    }

    public function setSundayOpeningHour(\DateTimeInterface $sundayOpeningHour): static
    {
        $this->sundayOpeningHour = $sundayOpeningHour;

        return $this;
    }

    public function getSundayClosingHour(): ?\DateTimeInterface
    {
        return $this->sundayClosingHour;
    }

    public function setSundayClosingHour(\DateTimeInterface $sundayClosingHour): static
    {
        $this->sundayClosingHour = $sundayClosingHour;

        return $this;
    }
}
