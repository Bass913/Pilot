<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Services;

class ServicesFixtures extends Fixture
{
    public const SERVICE_REFERENCE = [
        "service-vidange" => "Vidange",
        "service-pneus" => "Changement de pneus",
        "service-freins" => "Changement des freins",
        "service-controle" => "Contrôle technique"
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
