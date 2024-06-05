<?php

namespace App\DataFixtures;

use App\Entity\Rating;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Review;
use Faker\Factory;
use App\Repository\ReviewRepository;
use App\Entity\Company;

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

        $companyRatings = [];
        $companyReviewCounts = [];

        for ($i = 0; $i < self::REVIEW_REFERENCE_COUNT; $i++) {
            foreach ($companies as $company) {
                $review = new Review();
                $review->setDate($faker->date());
                $review->setComment($faker->sentence());
                $review->setCompany($company);
                for ($i = 0; $i < rand(3, 5); $i++) {
                    $rating = new Rating();
                    $rating->setReview($review);
                    $rating->setCategory($categories[array_rand($categories)]);
                    $rating->setValue($faker->randomDigitNotNull());
                    $manager->persist($rating);
                    $review->addRating($rating);
                }

                $manager->persist($review);

                // Mise à jour des totaux pour le calcul de la moyenne
                $companyId = $company->getId();
                if (!isset($companyRatings[$companyId])) {
                    $companyRatings[$companyId] = 0;
                    $companyReviewCounts[$companyId] = 0;
                }
                $companyRatings[$companyId] += $rating->getValue();
                $companyReviewCounts[$companyId]++;

                // Calcul et mise à jour de la moyenne directement
                $averageRating = $companyRatings[$companyId] / $companyReviewCounts[$companyId];
                $company->setReviewRating($averageRating);
                $manager->persist($company);
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