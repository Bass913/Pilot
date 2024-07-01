<?php

namespace App\DataFixtures;

use App\Entity\Unavailability;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use App\Entity\Company;

class UnavailabilityFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // Calculer les dates de début et de fin de la semaine en cours
        $startOfWeek = (new \DateTime())->modify('this week');
        $endOfWeek = (clone $startOfWeek)->modify('+18 days 23:59:59');

        // Récupérer le nombre d'utilisateurs
        $userCount = UserFixtures::EMPLOYEE_COUNT + UserFixtures::ADMIN_COUNT + UserFixtures::SPECIAL_USERS_COUNT;

        $companies = [];

        for ($i = 0; $i < 62; $i++) {
            $companies[] = $this->getReference('company-' . $i);
        }

        // Ajouter des indisponibilités pour les utilisateurs
        for ($i = 0; $i < $userCount; $i++) {
            $user = $this->getReference(UserFixtures::USER_REFERENCE_PREFIX . $i);

            // for ($j = 0; $j < 5; $j++) { // Ajoute 5 indisponibilités par utilisateur entre 8h et 18h
            //     $startDate = $faker->dateTimeBetween($startOfWeek->setTime(8, 0), $endOfWeek->setTime(18, 0));
            //     $endDate = (clone $startDate)->modify('+' . mt_rand(1, 3) . ' hours');

            //     // S'assurer que endDate ne dépasse pas 18h de la même journée
            //     if ($endDate->format('Y-m-d') != $startDate->format('Y-m-d') || $endDate > $startDate->setTime(18, 0)) {
            //         $endDate = (clone $startDate)->setTime(18, 0);
            //     }

            //     $unavailability = new Unavailability();
            //     $unavailability->setUser($user);
            //     $unavailability->setStartDate($startDate);
            //     $unavailability->setEndDate($endDate);

            //     $manager->persist($unavailability);
            // }

            // Ajouter une indisponibilité sur une journée complète au hasard (lundi à samedi)
            $randomDay = $faker->dateTimeBetween($startOfWeek, (clone $startOfWeek)->modify('+5 days'));
            $startOfDay = (clone $randomDay)->setTime(0, 0);
            $endOfDay = (clone $randomDay)->setTime(23, 59, 59);

            $unavailability = new Unavailability();
            $unavailability->setUser($user);
            $unavailability->setStartDate($startOfDay);
            $unavailability->setEndDate($endOfDay);

            $manager->persist($unavailability);
        }

        // Ajouter des indisponibilités pour les entreprises
        foreach ($companies as $company) {
            // Une indisponibilité pour toute la journée (8h à 18h) sur un jour aléatoire de la semaine
            $randomDay = $faker->dateTimeBetween($startOfWeek, $endOfWeek);
            $startOfDay = (clone $randomDay)->setTime(8, 0);
            $endOfDay = (clone $randomDay)->setTime(18, 0);

            $unavailability = new Unavailability();
            $unavailability->setCompany($company);
            $unavailability->setStartDate($startOfDay);
            $unavailability->setEndDate($endOfDay);

            $manager->persist($unavailability);

            // Une indisponibilité entre midi et 14h pour chaque jour de la semaine (lundi à samedi)
            for ($k = 0; $k < 6; $k++) {
                $specificDay = (clone $startOfWeek)->modify("+$k day");
                $startMidday = (clone $specificDay)->setTime(12, 0);
                $endMidday = (clone $startMidday)->modify('+2 hours');

                // S'assurer que endMidday ne dépasse pas la fin de la période définie
                if ($endMidday > $endOfWeek) {
                    $endMidday = $endOfWeek;
                }

                $unavailability = new Unavailability();
                $unavailability->setCompany($company);
                $unavailability->setStartDate($startMidday);
                $unavailability->setEndDate($endMidday);

                $manager->persist($unavailability);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class, CompanyFixtures::class];
    }
}
