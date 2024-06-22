<?php

namespace App\DataFixtures;

use App\Entity\Speciality;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SpecialityFixtures extends Fixture
{
    public const SPECIALITY_REFERENCE = [
        "mechanics" => "mechanics",
        "bodywork" => "bodywork",
        "electricity" => "electricity",
        "air-conditioning" => "air-conditioning",
        "diagnostics" => "diagnostics",
        "tire-repair" => "tire-repair",
        "restoration" => "restoration",
        "tuning" => "tuning",
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
