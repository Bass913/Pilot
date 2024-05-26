<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\CategoryReview;



class CategoryReviewFixtures extends Fixture
{
    public const CATEGORY_REFERENCE = [
        "qualite" => "Qualité de service",
        "prix" => "Prix",
        "delai" => "Délai",
        "accueil" => "Accueil"
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORY_REFERENCE as $key => $name) {
            $category = new CategoryReview();
            $category->setName($name);
            $manager->persist($category);
            $this->addReference($key, $category);
        }

        $manager->flush();
    }

}
