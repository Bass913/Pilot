<?php

namespace App\DataFixtures;

use App\Entity\Quantity;
use App\Repository\IngredientRepository;
use App\Repository\RecipeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class QuantityFixtures extends Fixture implements DependentFixtureInterface
{
    private RecipeRepository $recipeRepository;
    private IngredientRepository $ingredientRepository;


    public function __construct(RecipeRepository $recipeRepository, IngredientRepository $ingredientRepository)
    {
        $this->recipeRepository = $recipeRepository;
        $this->ingredientRepository = $ingredientRepository;
    }

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $recipes = $this->recipeRepository->findAll();
        $ingredients = $this->ingredientRepository->findAll();


        for ($i = 0; $i < 10; $i++) {
            $quantity = new Quantity();
            $quantity->setAmount($faker->numberBetween(1, 10));
            $randomRecipe = $faker->randomElement($recipes);
            $quantity->setRecipe($randomRecipe);
            $randomIngredient = $faker->randomElement($ingredients);
            $quantity->setIngredient($randomIngredient);

            $manager->persist($quantity);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [RecipeFixtures::class, IngredientFixtures::class];

    }
}
