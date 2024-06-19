<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Service;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ServicesFixtures extends Fixture implements DependentFixtureInterface
{public const SERVICE_REFERENCE = [
    "service-vidange" => "Vidange",
    "service-pneus" => "Changement de pneus",
    "service-freins" => "Changement des freins",
    "service-controle" => "Contrôle technique",
    "service-batterie" => "Remplacement de batterie",
    "service-climatisation" => "Recharge de climatisation",
    "service-parallélisme" => "Réglage du parallélisme",
    "service-echappement" => "Réparation de l'échappement",
    "service-amortisseurs" => "Remplacement des amortisseurs",
    "service-carrosserie" => "Réparation de carrosserie",
    "service-parebrise" => "Remplacement de pare-brise",
    "service-diagnostique" => "Diagnostic électronique",
    "service-embrayage" => "Remplacement de l'embrayage",
    "service-plaquettes" => "Changement des plaquettes de frein",
    "service-balais-essuie-glace" => "Remplacement des balais d'essuie-glace",
    "service-revision" => "Révision complète",
    "service-filtre" => "Changement de filtre à air",
    "service-alternateur" => "Remplacement de l'alternateur",
    "service-essai-route" => "Essai sur route",
    "service-direction" => "Réparation de la direction",
    "service-radiateur" => "Remplacement du radiateur",
    "service-courroie" => "Changement de la courroie de distribution",
    "service-feux" => "Réparation des feux et éclairage",
    "service-moteur" => "Réparation du moteur"
];


    public function load(ObjectManager $manager)
    {
        $specialities = [];

        foreach (array_keys(SpecialityFixtures::SPECIALITY_REFERENCE) as $key) {
            $specialities[$key] = $this->getReference($key);
        }

        foreach (self::SERVICE_REFERENCE as $key => $name) {
            $service = new Service();
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
