<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Services;

class SpecialityFixtures extends Fixture
{
    public const SERVICE_REFERENCE = [
        "mecanique" => "Vidange",
        "carrosserie" => "Changement de pneus",
        "electricite" => "Changement des freins",
        "climatisation" => "Contrôle technique",
        "diagnostic" => "Contrôle technique",
        "pneu" => "Contrôle technique",
        "restauration" => "Contrôle technique",
        "tuning" => "Contrôle technique"
    ];

    public function load(ObjectManager $manager)
    {


        foreach (self::SERVICE_REFERENCE as $key => $name) {
            $service = new Services();
            $service->setName($name);
            $manager->persist($service);
            $this->addReference($key, $service);
        }

        $manager->flush();
    }

}
