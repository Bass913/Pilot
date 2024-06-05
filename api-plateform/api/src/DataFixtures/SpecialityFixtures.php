<?php

namespace App\DataFixtures;

use App\Entity\Speciality;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SpecialityFixtures extends Fixture
{
    public const SPECIALITY_REFERENCE = [
        "mecanique" => "Mécanique générale",
        "carrosserie" => "Carrosserie",
        "electricite" => "Électricité mobile",
        "climatisation" => "Diagnostic électronique",
        "diagnostic" => "Réparation de moteurs diesel",
        "pneu" => "Réparation de pneus",
        "restauration" => "Restauration de voitures anciennes",
        "tuning" => "Tuning et personnalisation"
    ];

    public function load(ObjectManager $manager)
    {


        foreach (self::SPECIALITY_REFERENCE as $key => $name) {
            $speciality = new Speciality();
            $speciality->setName($name);
            $manager->persist($speciality);
            $this->addReference($key, $speciality);
        }

        $manager->flush();
    }
}
