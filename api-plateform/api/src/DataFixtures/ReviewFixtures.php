<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Review;
use Faker\Factory;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    public const REVIEW_REFERENCE_COUNT = 20;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $companies = [];

        for ($i = 0; $i < CompanyFixtures::COMPANY_REFERENCE_COUNT; $i++) {
            $companies[] = $this->getReference('company-' . $i);
        }

        for ($i = 0; $i < self::REVIEW_REFERENCE_COUNT; $i++) {
            $review = new Review();
            $review->setDate($faker->date());
            $review->setComment($faker->sentence());
            $review->setCompany($companies[array_rand($companies)]);
            $manager->persist($review);

            // Add reference for later use
            $this->addReference('review-' . $i, $review);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CompanyFixtures::class];
    }
}
