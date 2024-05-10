<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Services;

class ServicesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Création de quelques services :
        $servicesData = [
            [
                'name' => 'Coiffure',
            ],
            [
                'name' => 'Massage',
            ],
            [
                'name' => 'Manucure',
            ],
        ];

        foreach ($servicesData as $serviceData) {
            $service = new Services();
            $service->setName($serviceData['name']);

            $manager->persist($service);
        }

        $manager->flush();
    }
}
?>