<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Company;
use Faker\Factory;

class CompanyFixtures extends Fixture implements DependentFixtureInterface
{
    public const COMPANY_REFERENCE_COUNT = 62;
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // Retrieve all specialities
        $specialities = [];
        foreach (array_keys(SpecialityFixtures::SPECIALITY_REFERENCE) as $key) {
            $specialities[$key] = $this->getReference($key);
        }

        // List of image URLs
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

        $employeeCount = 0;
        $maxEmployeeCount = UserFixtures::EMPLOYEE_COUNT;

        $specialUserCount = UserFixtures::SPECIAL_USERS_COUNT;
        $counter = 0;
        for ($adminIndex = 0; $adminIndex < UserFixtures::ADMIN_COUNT; $adminIndex++) {
            $admin = $this->getReference(UserFixtures::ADMIN_REFERENCE_PREFIX . $adminIndex);

            for ($i = 0; $i < 3; $i++) {
                $company = new Company();
                $company->setName($faker->company());
                $company->setAddress($faker->streetAddress());
                $company->setDescription($faker->text());
                $company->setZipcode($faker->postcode());
                $company->setCity($faker->city());
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

                $company->setUser($admin);
                $manager->persist($company);

                $this->setReference('company-' . $counter, $company);
                $counter++;

                /*for ($j = 0; $j < 3 && $employeeCount < $maxEmployeeCount; $j++) {
                    if ($employeeCount < UserFixtures::EMPLOYEE_COUNT) {
                        $employee = $this->getReference(UserFixtures::EMPLOYEE_REFERENCE_PREFIX . $employeeCount++);
                        $employee->setCompany($company);
                        $manager->persist($employee);
                    }
                }*/

                $assignedEmployees = 0;
                $employeeIndex = 0;

                while ($assignedEmployees < 3 && $employeeIndex < UserFixtures::EMPLOYEE_COUNT) {
                    $employee = $this->getReference(UserFixtures::EMPLOYEE_REFERENCE_PREFIX . $employeeIndex++);

                    if ($employee->getCompany() === null) { // Check if employee is not already assigned
                        $employee->setCompany($company);
                        $manager->persist($employee);
                        $assignedEmployees++;
                    }
                }
            }
        }

        for ($specialUserIndex = 1; $specialUserIndex < $specialUserCount; $specialUserIndex++) {
            $specialUser = $this->getReference(UserFixtures::SPECIAL_USERS_REFERENCE_PREFIX . $specialUserIndex);
            if ($specialUserIndex === 3) {
                $specialUser->setCompany($this->getReference('company-' . $specialUserIndex));
                $manager->persist($specialUser);
            } else {
                for ($i = 0; $i < 3; $i++) {
                    $company = new Company();
                    $company->setName($faker->company());
                    $company->setAddress($faker->streetAddress());
                    $company->setDescription($faker->text());
                    $company->setZipcode($faker->postcode());
                    $company->setCity($faker->city());
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

                    $company->setUser($specialUser);
                    $manager->persist($company);

                    $this->setReference('company-' . $counter, $company);
                    $counter++;
                    $assignedEmployees = 0;
                    $employeeIndex = 0;

                    if($specialUser)

                    while ($assignedEmployees < 3 && $employeeIndex < UserFixtures::EMPLOYEE_COUNT) {
                        $employee = $this->getReference(UserFixtures::EMPLOYEE_REFERENCE_PREFIX . $employeeIndex++);

                        if ($employee->getCompany() === null) { // Check if employee is not already assigned
                            $employee->setCompany($company);
                            $manager->persist($employee);
                            $assignedEmployees++;
                        }
                    }
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [SpecialityFixtures::class, UserFixtures::class];
    }
}
