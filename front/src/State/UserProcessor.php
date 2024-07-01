<?php

namespace App\State;

use ApiPlatform\State\ProcessorInterface;
use App\Dto\UserInput;
use App\Entity\User;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use ApiPlatform\Metadata\Operation;


class UserProcessor implements ProcessorInterface
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private CompanyRepository $companyRepository;
    private Security $security;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        CompanyRepository $companyRepository,
        Security $security
    ) {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->companyRepository = $companyRepository;
        $this->security = $security;
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (!$data instanceof UserInput) {
            return;
        }

        $user = new User();
        $user->setEmail($data->email);
        $user->setFirstname($data->firstname);
        $user->setLastname($data->lastname);
        $user->setPhone($data->phone);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $data->password);
        $user->setPassword($hashedPassword);

        $company = $this->companyRepository->find($data->companyId);
        var_dump($company);
        die();
        if ($company) {
            $user->setCompany($company);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

}
