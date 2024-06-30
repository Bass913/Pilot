<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Company;
use App\Entity\User;
use Faker\Factory;

class CompanyFixtures extends Fixture implements DependentFixtureInterface
{
    public const COMPANY_REFERENCE_COUNT = 20;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $specialities = [];

        // Load specialities from references
        foreach (array_keys(SpecialityFixtures::SPECIALITY_REFERENCE) as $key) {
            $specialities[$key] = $this->getReference($key);
        }

        $images = [
            "https://images.unsplash.com/photo-1551522435-a13afa10f103?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8Z2FyYWdlJTIwYXV0b3xlbnwwfDB8MHx8fDI%3D",
            "https://images.unsplash.com/photo-1570071677470-c04398af73ca?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8Z2FyYWdlJTIwYXV0b3xlbnwwfDB8MHx8fDI%3D",
            "https://images.unsplash.com/photo-1599256630445-67b5772b1204?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8Z2FyYWdlJTIwYXV0b3xlbnwwfDB8MHx8fDI%3D",
            "https://images.unsplash.com/photo-1487754180451-c456f719a1fc?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8Z2FyYWdlJTIwYXV0b3xlbnwwfDB8MHx8fDI%3D",
            "https://images.unsplash.com/photo-1591278169757-deac26e49555?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8Z2FyYWdlJTIwYXV0b3xlbnwwfDB8MHx8fDI%3D",
            "https://images.unsplash.com/photo-1631720040176-0d789a643a78?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MjF8fGdhcmFnZSUyMGF1dG98ZW58MHwwfDB8fHwy",
            "https://images.unsplash.com/photo-1524214786335-66456317bde6?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MjR8fGdhcmFnZSUyMGF1dG98ZW58MHwwfDB8fHwy",
            "https://images.unsplash.com/photo-1652987086612-d948b775d358?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MzR8fGdhcmFnZSUyMGF1dG98ZW58MHwwfDB8fHwy",
            "https://images.unsplash.com/photo-1591293836027-e05b48473b67?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NDZ8fGdhcmFnZSUyMGF1dG98ZW58MHwwfDB8fHwy",
            "https://images.unsplash.com/photo-1551522435-b2347f669045?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NTZ8fGdhcmFnZSUyMGF1dG98ZW58MHwwfDB8fHwy",
            "https://images.unsplash.com/photo-1526726538690-5cbf956ae2fd?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NjB8fGdhcmFnZSUyMGF1dG98ZW58MHwwfDB8fHwy"
        ];

        // Retrieve users with roles ROLE_EMPLOYEE and ROLE_ADMIN
        $employeeUsers = $this->getUsersByRole(['ROLE_EMPLOYEE', 'ROLE_ADMIN']);
        $adminUser = $this->getSingleUserByRole('ROLE_ADMIN');

        for ($i = 0; $i < self::COMPANY_REFERENCE_COUNT; $i++) {
            $company = $this->createCompany($faker, $specialities, $images);

            // Assign employees to the company
            for ($j = 0; $j < 3; $j++) {
                if (!empty($employeeUsers)) {
                    $user = array_shift($employeeUsers);
                    $user->setCompany($company);
                    $manager->persist($user);
                }
            }

            // Assign admin to the company
            if ($adminUser) {
                $adminUser->setCompany($company);
                $company->setUser($adminUser);
                $manager->persist($adminUser);
            }

            $manager->persist($company);
            $this->addReference('company-' . $i, $company);
        }

        $manager->flush();
    }

    private function createCompany($faker, $specialities, $images): Company
    {
        $company = new Company();
        $company->setName($faker->company());
        $company->setAddress($faker->streetAddress());
        $company->setDescription($faker->text());
        $company->setZipcode($faker->postcode());
        $company->setCity($faker->city());
        $company->setKbis($faker->fileExtension());
        $company->setActive($faker->boolean());
        $company->setLatitude($faker->latitude(48.024, 49.213));
        $company->setLongitude($faker->longitude(1.444, 3.538));

        $numImages = rand(2, 7);
        shuffle($images);
        $selectedImages = array_slice($images, 0, $numImages);
        $company->setImages($selectedImages);

        $company->setReviewRating($faker->randomFloat(1, 0, 5));
        $company->setReviewCount(ReviewFixtures::REVIEW_REFERENCE_COUNT);
        $company->setSpeciality($specialities[array_rand($specialities)]);

        return $company;
    }

    private function getUsersByRole(array $roles): array
    {
        $users = [];

        for ($i = 0; $this->hasReference(UserFixtures::USER_REFERENCE_PREFIX . $i); $i++) {
            $user = $this->getReference(UserFixtures::USER_REFERENCE_PREFIX . $i);
            if (array_intersect($roles, $user->getRoles()) && !$user->getCompany()) {
                $users[] = $user;
            }
        }

        return $users;
    }

    private function getSingleUserByRole(string $role): ?User
    {
        for ($i = 0; $this->hasReference(UserFixtures::USER_REFERENCE_PREFIX . $i); $i++) {
            $user = $this->getReference(UserFixtures::USER_REFERENCE_PREFIX . $i);
            if (in_array($role, $user->getRoles()) && !$user->getCompany()) {
                return $user;
            }
        }

        return null;
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class, SpecialityFixtures::class];
    }

}
