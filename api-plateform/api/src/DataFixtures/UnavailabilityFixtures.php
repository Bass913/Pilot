<?php

namespace App\DataFixtures;

use App\Entity\Unavailability;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use App\Entity\User;

class UnavailabilityFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // Calculer les dates de début et de fin de la semaine en cours
        $startOfWeek = (new \DateTime())->modify('this week');
        $endOfWeek = (clone $startOfWeek)->modify('+6 days 23:59:59');

        // Récupérer le nombre d'utilisateurs
        $userCount = 60;

        for ($i = 0; $i < $userCount; $i++) {
            $user = $this->getReference(UserFixtures::USER_REFERENCE_PREFIX . $i);

            for ($j = 0; $j < 5; $j++) { // Ajoute 5 indisponibilités par utilisateur
                $startDate = $faker->dateTimeBetween($startOfWeek, $endOfWeek);
                $endDate = (clone $startDate)->modify('+' . mt_rand(1, 3) . ' hours');

                // S'assurer que endDate ne dépasse pas la fin de la semaine
                if ($endDate > $endOfWeek) {
                    $endDate = $endOfWeek;
                }

                $unavailability = new Unavailability();
                $unavailability->setUser($user);
                $unavailability->setStartDate($startDate);
                $unavailability->setEndDate($endDate);

                $manager->persist($unavailability);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
