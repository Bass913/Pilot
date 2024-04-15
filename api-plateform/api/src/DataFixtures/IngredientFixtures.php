<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;

class IngredientFixtures extends Fixture
{

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($faker->unique()->word());
            $manager->persist($ingredient);
        }

        $manager->flush();
    }
}
