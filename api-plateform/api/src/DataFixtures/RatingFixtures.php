<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Rating;
use Faker\Factory;

class RatingFixtures extends Fixture implements DependentFixtureInterface
{
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $categories = [];

        foreach (array_keys(CategoryReviewFixtures::CATEGORY_REFERENCE) as $key) {
            $categories[$key] = $this->getReference($key);
        }
        $reviews = [];

        for ($i = 0; $i < ReviewFixtures::REVIEW_REFERENCE_COUNT; $i++) {
            $reviews[] = $this->getReference('review-' . $i);
        }


        foreach ($reviews as $review) {
            $rating = new Rating();
            $rating->setReview($review);
            $rating->setValue($faker->randomDigit());
            $rating->setCategory($categories[array_rand($categories)]);
            $manager->persist($rating);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ReviewFixtures::class, CategoryReviewFixtures::class];
    }

}
