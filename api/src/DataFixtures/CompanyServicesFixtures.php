<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\CompanyService;
use Faker\Factory;

class CompanyServicesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $companies = [];

        for ($i = 0; $i < CompanyFixtures::COMPANY_REFERENCE_COUNT; $i++) {
            $companies[] = $this->getReference('company-' . $i);
        }

        $services = [];

        foreach (array_keys(ServicesFixtures::SERVICE_REFERENCE) as $key) {
            $services[$key] = $this->getReference($key);
        }

        foreach ($companies as $companyData) {
            $shuffledServices = $services;
            shuffle($shuffledServices);

            $selectedServices = array_slice($shuffledServices, 0, 4);

            foreach ($selectedServices as $service) {
                $companyService = new CompanyService();
                $companyService->setCompany($companyData);
                $companyService->setPrice($faker->numberBetween(50, 100));
                $companyService->setDuration(30);
                $companyService->setService($service);

                $manager->persist($companyService);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CompanyFixtures::class, ServicesFixtures::class];
    }
}
