<?php

namespace App\DataFixtures;

use App\Entity\Schedule;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ScheduleFixtures extends Fixture implements DependentFixtureInterface
{
    public const SCHEDULE_REFERENCE = [
        "monday" => [
            "day" => "monday",
            "startTime" => "08:00",
            "endTime" => "18:00"
        ],
        "thursday" => [
            "day" => "thursday",
            "startTime" => "08:00",
            "endTime" => "18:00"
        ],
        "wednesday" => [
            "day" => "wednesday",
            "startTime" => "08:00",
            "endTime" => "16:00"
        ],
        "tuesday" => [
            "day" => "tuesday",
            "startTime" => "08:00",
            "endTime" => "18:00"
        ],
        "friday" => [
            "day" => "friday",
            "startTime" => "08:00",
            "endTime" => "12:00"
        ],
        "saturday" => [
            "day" => "saturday",
            "startTime" => "08:00",
            "endTime" => "18:00"
        ],
        "sunday" => [
            "day" => "sunday",
            "startTime" => null,
            "endTime" => null
        ],
    ];

    public function load(ObjectManager $manager)
    {

        $companies = $manager->getRepository(\App\Entity\Company::class)->findAll();

        $users = [];
        $totalUsers = UserFixtures::EMPLOYEE_COUNT + UserFixtures::ADMIN_COUNT + UserFixtures::SPECIAL_USERS_COUNT;
        for ($i = 1; $i < $totalUsers; $i++) { // Ajustez ce nombre selon le nombre total d'utilisateurs
            $users[] = $this->getReference(UserFixtures::USER_REFERENCE_PREFIX . $i);
        }

        foreach ($companies as $company) {
            foreach (self::SCHEDULE_REFERENCE as $day) {
                $schedule = new Schedule();
                $schedule->setDayOfWeek($day["day"]);
                $schedule->setStartTime($day["startTime"] ? new DateTime($day["startTime"]) : null);
                $schedule->setEndTime($day["endTime"] ? new DateTime($day["endTime"]) : null);
                $company->addSchedule($schedule);
                $manager->persist($schedule);
            }
            $manager->persist($company);
        }


        // Ajouter des horaires à chaque utilisateur
        foreach ($users as $user) {
            foreach (self::SCHEDULE_REFERENCE as $day) {
                $schedule = new Schedule();
                $schedule->setDayOfWeek($day["day"]);
                $schedule->setStartTime($day["startTime"] ? new DateTime($day["startTime"]) : null);
                $schedule->setEndTime($day["endTime"] ? new DateTime($day["endTime"]) : null);
                $user->addSchedule($schedule);
                $manager->persist($schedule);
            }
            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CompanyFixtures::class, UserFixtures::class];
    }
}
