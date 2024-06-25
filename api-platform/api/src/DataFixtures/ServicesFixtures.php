<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Service;
use App\Entity\Speciality;

class ServicesFixtures extends Fixture
{
    public const SERVICE_REFERENCE = [
        "oil-change",
        "tire-change",
        "brake-change",
        "technical-inspection",
        "battery-replacement",
        "air-conditioning-recharge",
        "wheel-alignment",
        "exhaust-repair",
        "shock-absorber-replacement",
        "body-repair",
        "windshield-replacement",
        "electronic-diagnosis",
        "clutch-replacement",
        "brake-pad-change",
        "wiper-blade-replacement",
        "full-service",
        "air-filter-change",
        "alternator-replacement",
        "road-test",
        "steering-repair",
        "radiator-replacement",
        "timing-belt-change",
        "light-repair",
        "engine-repair"
    ];

    public function load(ObjectManager $manager)
    {
        // Get all specialities using references from SpecialityFixtures
        $specialities = [];
        foreach (SpecialityFixtures::SPECIALITY_REFERENCE as $key => $name) {
            $specialities[$key] = $this->getReference($key);
        }

        foreach (self::SERVICE_REFERENCE as $key => $name) {
            $service = new Service();
            $service->setName($name);
            $service->setSpeciality($specialities[array_rand($specialities)]);
            $manager->persist($service);
            $this->addReference($key, $service); // Utilisation de $key comme référence
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [SpecialityFixtures::class];
    }
}
