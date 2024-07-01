<?php

namespace App\Dto;


use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
class UserInput
{
    #[Groups(['user:create'])]
    #[Assert\NotBlank]
    #[Assert\Email]
    public ?string $email = null;

    #[Groups(['user:create'])]
    #[Assert\NotBlank]
    public ?string $firstname = null;

    #[Groups(['user:create'])]
    #[Assert\NotBlank]
    public ?string $lastname = null;

    #[Groups(['user:create'])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8)]
    public ?string $password = null;

    #[Groups(['user:create'])]
    #[Assert\NotBlank]
    public ?string $phone = null;

    #[Groups(['user:create'])]
    #[Assert\NotBlank]
    public ?string $companyId = null;

}
