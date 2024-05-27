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

        // Tableau des URLs d'images disponibles
        $images = [
            "https://images.unsplash.com/photo-1551522435-a13afa10f103",
            "https://images.unsplash.com/photo-1570071677470-c04398af73ca",
            "https://images.unsplash.com/photo-1517430816045-df4b7de5438a",
            "https://images.unsplash.com/photo-1494173853739-c21f58b16055",
            "https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0",
            "https://images.unsplash.com/photo-1503023345310-bd7c1de61c7d",
            "https://images.unsplash.com/photo-1481277542470-605612bd2d61",
        ];

        // Création de quelques companies :
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

            $numImages = rand(2, 7);

            shuffle($images);

            $selectedImages = array_slice($images, 0, $numImages);

            $company->setImages($selectedImages);

            $company->setReviewRating($faker->randomFloat(1, 0, 5));
            $company->setReviewCount(ReviewFixtures::REVIEW_REFERENCE_COUNT);
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
