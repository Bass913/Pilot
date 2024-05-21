<?php

namespace App\DataFixtures;

use App\Entity\Services;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\CompanyServices;
use App\Entity\Company;
use Faker\Factory;



class CompanyServicesFixtures extends Fixture implements DependentFixtureInterface
{


    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $companies = array_map(fn(string $key): Company => $this->getReference($key), array_keys(CompanyFixtures::COMPANY_REFERENCE));

        $services = array_map(fn(string $key): Services => $this->getReference($key), array_keys(ServicesFixtures::SERVICE_REFERENCE));



        foreach ($companies as $companyData) {
            $companyService = new CompanyServices();
            $companyService->setCompany($companyData);
            $companyService->setPrice($faker->numberBetween(50, 100));
            $companyService->setDuration(30);
            foreach ($services as $service) {
                $companyService->setService($service);
            }

            $manager->persist($companyService);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CompanyFixtures::class, ServicesFixtures::class];
    }
}
