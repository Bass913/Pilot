<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class RecipeFixtures extends Fixture
{
    private CategoryRepository $categoryRepository;
    private Generator $faker;


    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->faker = Factory::create('fr_FR');
    }

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Get existing categories
        $categories = $this->categoryRepository->findAll();

        for ($i = 0; $i < 10; $i++) {
            $recipe = new Recipe();
            $recipe->setTitle($faker->sentence(3));
            $recipe->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s')));
            $recipe->setDescription($faker->paragraph());
            $recipe->setDuration($faker->numberBetween(10, 120));
            $recipe->setDifficulty($faker->randomElement(['easy', 'medium', 'difficult']));
            $recipe->setImage($faker->imageUrl());
            $recipe->setStatus($faker->boolean());

            // Assign a random category to the recipe
            $randomCategory = $faker->randomElement($categories);
            $recipe->setCategory($randomCategory);

            $manager->persist($recipe);
        }

        $manager->flush();
    }
}
