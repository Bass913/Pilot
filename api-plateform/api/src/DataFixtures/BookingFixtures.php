<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class BookingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $companyServices = $manager->getRepository(\App\Entity\CompanyService::class)->findAll();
        $users = $manager->getRepository(\App\Entity\User::class)->findAll();



        foreach ($users as $user) {
            for ($j = 0; $j < 3; $j++) { // Ajoute 3 rendez-vous par utilisateur
                $booking = new Booking();
                $companyService = $companyServices[array_rand($companyServices)];
                $booking->setCompanyService($companyService);
                $booking->setClient($user);

                $company = $companyService->getCompany();
                $employeesInCompany = array_filter($users, function ($u) use ($company) {
                    return $u->getCompany() === $company;
                });

                if (empty($employeesInCompany)) {
                    continue;
                }

                $employee = $employeesInCompany[array_rand($employeesInCompany)];
                $booking->setEmployee($employee);

                //$employee = $users[array_rand($users)];
                //$booking->setEmployee($employee);
                $booking->setCompany($company);
                $booking->setStatus("test");
                // Randomly decide if the booking date should be in the upcoming week or in the past
                $isUpcomingWeek = (bool) random_int(0, 1);
                $startDate = new \DateTime();

                if ($isUpcomingWeek) {
                    // Generate a random date within the upcoming week
                    $daysToAdd = random_int(0, 6); // Any day within the next week
                    $startDate->modify("+$daysToAdd days");
                } else {
                    // Generate a random date in the past
                    $daysToSubtract = random_int(1, 365); // Any day in the past year
                    $startDate->modify("-$daysToSubtract days");
                }

                if ($startDate->format('l') === 'Friday') {
                    $hour = random_int(8, 12); // Between 8 AM and 11 AM
                } else {
                    $hour = random_int(8, 18); // Between 8 AM and 4 PM for other days
                }
                // Generate a random hour between 8 and 17 (8 AM to 6 PM)
                $startDate->setTime($hour, 0);

                $booking->setStartDate($startDate->format(\DateTime::ATOM));


                $manager->persist($booking);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CompanyServicesFixtures::class, UserFixtures::class];
    }
}
