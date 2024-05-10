<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\CompanyServices;
use App\Entity\Company;

class CompanyServicesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Création de quelques services :
        $companies = [
            ['name' => 'Entreprise A'],
            ['name' => 'Entreprise B'],
        ];

        $servicesData = [
            [
                'price' => '20.00',
                'duration' => 60,
                'company' => 'Entreprise A',
            ],
            [
                'price' => '30.00',
                'duration' => 45,
                'company' => 'Entreprise B',
            ],
        ];

        foreach ($companies as $companyData) {
            $company = new Company();
            $company->setName($companyData['name']);
            $manager->persist($company);

            foreach ($servicesData as $serviceData) {
                if ($serviceData['company'] === $companyData['name']) {
                    $service = new CompanyServices();
                    $service->setPrice($serviceData['price']);
                    $service->setDuration($serviceData['duration']);
                    $service->setCompany($company);

                    $manager->persist($service);
                }
            }
        }

        $manager->flush();
    }
}
