<?php 
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Company;

class CompanyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Création de quelques company :
        $companiesData = [
            [
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
            [
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

        foreach ($companiesData as $companyData) {
            $company = new Company();
            $company->setName($companyData['name']);
            $company->setAddress($companyData['address']);
            $company->setDescription($companyData['description']);
            $company->setZipcode($companyData['zipcode']);
            $company->setCity($companyData['city']);
            $company->setKbis($companyData['kbis']);
            $company->setActive($companyData['active']);
            $company->setLatitude($companyData['latitude']);
            $company->setLongitude($companyData['longitude']);

            $manager->persist($company);
        }

        $manager->flush();
    }
}
?>