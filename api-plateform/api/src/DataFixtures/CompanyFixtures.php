<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Company;
use Faker\Factory;


class CompanyFixtures extends Fixture implements DependentFixtureInterface
{
    public const COMPANY_REFERENCE_COUNT = 20;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $specialities = [];

        foreach (array_keys(SpecialityFixtures::SPECIALITY_REFERENCE) as $key) {
            $specialities[$key] = $this->getReference($key);
        }

        // Création de quelques company :

        for ($i = 0; $i < self::COMPANY_REFERENCE_COUNT; $i++) {
            $company = new Company();
            $company->setName($faker->company());
            $company->setAddress($faker->streetAddress());
            $company->setDescription($faker->text());
            $company->setZipcode($faker->postcode());
            $company->setCity($faker->city());
            $company->setKbis($faker->fileExtension());
            $company->setActive($faker->boolean());
            $company->setLatitude($faker->latitude(-90, 90));
            $company->setLongitude($faker->longitude(-180, 180));
            $company->setReviewRating($faker->randomFloat(1, 0, 5));
            $company->setSpeciality($specialities[array_rand($specialities)]);
            $manager->persist($company);
            $this->addReference('company-' . $i, $company);
        }

        $manager->flush();

    }

    public function getDependencies(): array
    {
        return [SpecialityFixtures::class];
    }
}
