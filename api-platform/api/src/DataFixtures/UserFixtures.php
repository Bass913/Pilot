<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $passwordHasher;
    const USER_REFERENCE_PREFIX = 'user-';

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $companies = [];

        for ($i = 0; $i < CompanyFixtures::COMPANY_REFERENCE_COUNT; $i++) {
            $companies[] = $this->getReference('company-' . $i);
        }

        $userCount = 0;
        $usersToPersist = [];
        foreach ($companies as $company) {
            for ($i = 0; $i < 3; $i++) {
                $usersToPersist[] = $this->createUser($faker->firstName(), $faker->lastName(), $faker->email(), ['ROLE_USER', 'ROLE_EMPLOYEE'], $faker->e164PhoneNumber(), $company, 'test', $userCount);
                $userCount++;
            }
            $usersToPersist[] = $this->createUser($faker->firstName(), $faker->lastName(), $faker->email(), ['ROLE_USER', 'ROLE_EMPLOYEE', 'ROLE_ADMIN'], $faker->e164PhoneNumber(), $company, 'test', $userCount);
        }

        $usersToPersist[] = $this->createUser('John', 'Doe', 'user@user.fr', ['ROLE_USER'], '0102030405', $companies[array_rand($companies)], 'test', $userCount);
        $userCount++;
        $usersToPersist[] = $this->createUser('Admin', 'Administrateur', 'admin@admin.fr', ['ROLE_USER', 'ROLE_ADMIN'], $faker->phoneNumber(), $companies[array_rand($companies)], 'test', $userCount);
        $userCount++;
        $usersToPersist[] = $this->createUser('Super', 'Administrateur', 'super@admin.fr', ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPERADMIN'], $faker->phoneNumber(), $companies[array_rand($companies)], 'test', $userCount);
        $userCount++;
        $usersToPersist[] = $this->createUser('Employee', 'Dumois', 'employee@dumois.fr', ['ROLE_USER', 'ROLE_EMPLOYEE'], $faker->phoneNumber(), $companies[array_rand($companies)], 'test', $userCount);


        foreach ($usersToPersist as $user) {
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function createUser(string $firstName, string $lastName, string $email, array $roles, string $phone, $company, string $plainPassword, int $userCount)
    {
        $user = new User();
        $user->setFirstname($firstName);
        $user->setLastname($lastName);
        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setActive(true);

        if (in_array("ROLE_ADMIN", $roles)) {
            $company->setUser($user);
        }
        $user->setPhone($phone);
        $user->setCompany($company);
        $emails = ["user@user.fr", "admin@admin.fr", "super@admin.fr", "employee@dumois.fr"];
        if (in_array($email, $emails)) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        } else {
            $user->setPassword($plainPassword);
        }

        if (!in_array("ROLE_ADMIN", $roles)) {
            $this->addReference(self::USER_REFERENCE_PREFIX . $userCount, $user);
        }

        return $user;
    }

    public function getDependencies(): array
    {
        return [CompanyFixtures::class];
    }
}
