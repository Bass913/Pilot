<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\CompanyRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    private CompanyRepository $companyRepository;

    public function __construct(UserPasswordHasherInterface $passwordHasher, CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $companies = $this->companyRepository->findAll();

        $user = new User();
        $user->setFirstname('John');
        $user->setLastname('Doe');
        $user->setEmail('user@user.fr');
        $user->setRoles(['ROLE_USER']);
        $user->setCompany($companies[0]);
        $plaintextPassword = 'test';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);

        $manager->persist($user);

        $manager->flush();
    }
}
