<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Company;

class CompanyFixtures extends Fixture
{
    public const COMPANY_REFERENCE = [
        "company-entreprise-a" => [
            'name' => 'Entreprise A',
            'address' => '123 Rue de la Test',
            'description' => 'Description de l\'entreprise A.',
            'zipcode' => '75001',
            'city' => 'Paris',
            'kbis' => 'https://exemple.com/kbis/entreprise-a',
            'active' => true,
            'latitude' => 48.8566,
            'longitude' => 2.3522,
        ],
        "company-entreprise-b" => [
            'name' => 'Entreprise B',
            'address' => '456 Avenue du Test',
            'description' => 'Description de l\'entreprise B.',
            'zipcode' => '69001',
            'city' => 'Lyon',
            'kbis' => 'https://exemple.com/kbis/entreprise-b',
            'active' => true,
            'latitude' => 45.75,
            'longitude' => 4.85,
        ],
    ];
    public function load(ObjectManager $manager)
    {
        // Création de quelques company :


        foreach (self::COMPANY_REFERENCE as $key => $values) {
            $company = new Company();
            $company->setName($values["name"]);
            $company->setAddress($values["address"]);
            $company->setDescription($values['description']);
            $company->setZipcode($values['zipcode']);
            $company->setCity($values['city']);
            $company->setKbis($values['kbis']);
            $company->setActive($values['active']);
            $company->setLatitude($values['latitude']);
            $company->setLongitude($values['longitude']);
            $company->setReviewRating(0);
            $manager->persist($company);
            $this->addReference($key, $company);

        }

        $manager->flush();
    }
}
