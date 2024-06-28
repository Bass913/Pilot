<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $passwordHasher;
    private $faker;

    const USER_REFERENCE_PREFIX = 'user-';

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $companies = [];
        for ($i = 0; $i < CompanyFixtures::COMPANY_REFERENCE_COUNT; $i++) {
            $companies[] = $this->getReference('company-' . $i);
        }

        $specialities = [];
        foreach (array_keys(SpecialityFixtures::SPECIALITY_REFERENCE) as $key) {
            $specialities[$key] = $this->getReference($key);
        }


        $usersToPersist = [];
        $userCount = 0;

        foreach ($companies as $company) {
            for ($i = 0; $i < 3; $i++) {
                $usersToPersist[] = $this->createUser(
                    $this->faker->firstName(),
                    $this->faker->lastName(),
                    $this->faker->email(),
                    ['ROLE_USER', 'ROLE_EMPLOYEE'],
                    $this->faker->e164PhoneNumber(),
                    $company,
                    'test',
                    $userCount++
                );
            }

            $adminUser = $this->createUser(
                $this->faker->firstName(),
                $this->faker->lastName(),
                $this->faker->email(),
                ['ROLE_USER', 'ROLE_EMPLOYEE', 'ROLE_ADMIN'],
                $this->faker->e164PhoneNumber(),
                $company,
                'test',
                $userCount++
            );

            // Create new companies for the admin user
            for ($i = 0; $i < 3; $i++) {
                $newCompany = new Company();
                $newCompany->setName($this->faker->company());
                $newCompany->setAddress($this->faker->streetAddress());
                $newCompany->setDescription($this->faker->text());
                $newCompany->setZipcode($this->faker->postcode());
                $newCompany->setCity($this->faker->city());
                $newCompany->setKbis($this->faker->fileExtension());
                $newCompany->setActive($this->faker->boolean());
                $newCompany->setLatitude($this->faker->latitude(48.024, 49.213));
                $newCompany->setLongitude($this->faker->longitude(1.444, 3.538));
                $newCompany->setReviewRating($this->faker->randomFloat(1, 0, 5));
                $newCompany->setReviewCount(ReviewFixtures::REVIEW_REFERENCE_COUNT);
                $newCompany->setSpeciality($specialities[array_rand($specialities)]);
                $newCompany->setUser($adminUser); // Set the admin user for each new company

                $manager->persist($newCompany);
            }

            $usersToPersist[] = $adminUser;
        }

        // Special users
        $usersToPersist[] = $this->createUser('John', 'Doe', 'user@user.fr', ['ROLE_USER'], '0102030405', $companies[array_rand($companies)], 'test', $userCount++);
        $usersToPersist[] = $this->createUser('Admin', 'Administrateur', 'admin@admin.fr', ['ROLE_USER', 'ROLE_ADMIN'], $this->faker->phoneNumber(), $companies[array_rand($companies)], 'test', $userCount++);
        $usersToPersist[] = $this->createUser('Super', 'Administrateur', 'super@admin.fr', ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPERADMIN'], $this->faker->phoneNumber(), $companies[array_rand($companies)], 'test', $userCount++);
        $usersToPersist[] = $this->createUser('Employee', 'Dumois', 'employee@dumois.fr', ['ROLE_USER', 'ROLE_EMPLOYEE'], $this->faker->phoneNumber(), $companies[array_rand($companies)], 'test', $userCount++);

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
        $user->setPhone($phone);
        $user->setCompany($company);

        $emails = ["user@user.fr", "admin@admin.fr", "super@admin.fr", "employee@dumois.fr"];
        if (in_array($email, $emails)) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        } else {
            $user->setPassword($plainPassword);
        }

        $this->addReference(self::USER_REFERENCE_PREFIX . $userCount, $user);



        return $user;
    }

    public function getDependencies(): array
    {
        return [CompanyFixtures::class];
    }
}
