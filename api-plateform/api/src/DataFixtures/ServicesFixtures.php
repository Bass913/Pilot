<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Services;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ServicesFixtures extends Fixture implements DependentFixtureInterface
{
    public const SERVICE_REFERENCE = [
        "service-vidange" => "Vidange",
        "service-pneus" => "Changement de pneus",
        "service-freins" => "Changement des freins",
        "service-controle" => "Contrôle technique"
    ];

    public function load(ObjectManager $manager)
    {
        $specialities = [];

        foreach (array_keys(SpecialityFixtures::SPECIALITY_REFERENCE) as $key) {
            $specialities[$key] = $this->getReference($key);
        }

        foreach (self::SERVICE_REFERENCE as $key => $name) {
            $service = new Services();
            $service->setName($name);
            $service->setSpeciality($specialities[array_rand($specialities)]);
            $manager->persist($service);
            $this->addReference($key, $service);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [SpecialityFixtures::class];
    }
}
