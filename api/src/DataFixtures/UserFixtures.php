<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    private $faker;

    const USER_REFERENCE_PREFIX = 'user-';
    const ADMIN_REFERENCE_PREFIX = 'admin-';
    const EMPLOYEE_REFERENCE_PREFIX = 'employee-';
    const USER_COUNT = 300;
    const ADMIN_COUNT = 20;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $userCount = 0;
        $adminCount = 0;
        $employeeCount = 0;
        $usersToPersist = [];

        $specialUsers = [
            ['John', 'Doe', 'user@user.fr', ['ROLE_USER'], '0102030405'],
            ['Admin', 'Administrateur', 'admin@admin.fr', ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_EMPLOYEE'], $this->faker->phoneNumber()],
            ['Super', 'Administrateur', 'super@admin.fr', ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPERADMIN'], $this->faker->phoneNumber()],
            ['Employee', 'Dumois', 'employee@dumois.fr', ['ROLE_USER', 'ROLE_EMPLOYEE'], $this->faker->phoneNumber()],
        ];

        foreach ($specialUsers as $specialUser) {
            $usersToPersist[] = $this->createUser(
                $specialUser[0],
                $specialUser[1],
                $specialUser[2],
                $specialUser[3],
                $specialUser[4],
                'test',
                $userCount++
            );
        }

        for ($i = 0; $i < self::USER_COUNT; $i++) {
            $user = $this->createUser(
                $this->faker->firstName(),
                $this->faker->lastName(),
                $this->faker->email(),
                ['ROLE_USER', 'ROLE_EMPLOYEE'],
                $this->faker->e164PhoneNumber(),
                'test',
                $userCount++
            );
            $usersToPersist[] = $user;
            $this->addReference(self::EMPLOYEE_REFERENCE_PREFIX . $employeeCount++, $user);
        }

        for ($i = 0; $i < self::ADMIN_COUNT; $i++) {
            $user = $this->createUser(
                $this->faker->firstName(),
                $this->faker->lastName(),
                $this->faker->email(),
                ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_EMPLOYEE'],
                $this->faker->e164PhoneNumber(),
                'test',
                $userCount++
            );
            $usersToPersist[] = $user;
            $this->addReference(self::ADMIN_REFERENCE_PREFIX . $adminCount++, $user);
        }

        foreach ($usersToPersist as $user) {
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function createUser(string $firstName, string $lastName, string $email, array $roles, string $phone, string $plainPassword, int $userCount): User
    {
        $user = new User();
        $user->setFirstname($firstName);
        $user->setLastname($lastName);
        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setActive(true);
        $user->setPhone($phone);

        // Hash password for special users
        if (in_array($email, ["user@user.fr", "admin@admin.fr", "super@admin.fr", "employee@dumois.fr"])) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        } else {
            $user->setPassword($plainPassword);
        }

        $this->addReference(self::USER_REFERENCE_PREFIX . $userCount, $user);

        return $user;
    }
}
