<?php

namespace App\DataFixtures;

use App\Entity\Rating;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Review;
use Faker\Factory;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    public const REVIEW_REFERENCE_COUNT = 5;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $companies = [];

        for ($i = 0; $i < CompanyFixtures::COMPANY_REFERENCE_COUNT; $i++) {
            $companies[] = $this->getReference('company-' . $i);
        }

        $categories = [];

        foreach (array_keys(CategoryReviewFixtures::CATEGORY_REFERENCE) as $key) {
            $categories[$key] = $this->getReference($key);
        }

        for ($i = 0; $i < self::REVIEW_REFERENCE_COUNT; $i++) {
            foreach($companies as $company) {
                $review = new Review();
                $review->setDate($faker->date());
                $review->setComment($faker->sentence());
                $review->setCompany($company);
                $rating = new Rating();
                $rating->setReview($review);
                $rating->setCategory($categories[array_rand($categories)]);
                $rating->setValue($faker->randomDigit());
                $manager->persist($rating);
                $review->addRating($rating);
                $manager->persist($review);
            }
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
