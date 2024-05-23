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
            "day" => "Lundi",
            "startTime" => "08:00",
            "endTime" => "18:00"
        ],
        "thursday" => [
            "day" => "Mardi",
            "startTime" => "08:00",
            "endTime" => "18:00"
        ],
        "wednesday" => [
            "day" => "Mercredi",
            "startTime" => "08:00",
            "endTime" => "16:00"
        ],
        "tuesday" => [
            "day" => "Jeudi",
            "startTime" => "08:00",
            "endTime" => "18:00"
        ],
        "friday" => [
            "day" => "Vendredi",
            "startTime" => "08:00",
            "endTime" => "12:00"
        ],
        "saturday" => [
            "day" => "Samedi",
            "startTime" => "08:00",
            "endTime" => "18:00"
        ],
        "sunday" => [
            "day" => "Dimanche",
            "startTime" => "08:00",
            "endTime" => "18:00"
        ],
    ];

    public function load(ObjectManager $manager)
    {

        $companies = [];

        for ($i = 0; $i < CompanyFixtures::COMPANY_REFERENCE_COUNT; $i++) {
            $companies[] = $this->getReference('company-' . $i);
        }

        foreach ($companies as $company) {
            foreach (self::SCHEDULE_REFERENCE as $day) {
                $schedule = new Schedule();
                $schedule->setDayOfWeek($day["day"]);
                $schedule->setStartTime(new DateTime($day["startTime"]));
                $schedule->setEndTime(new DateTime($day["endTime"]));
                $company->addSchedule($schedule);
                $manager->persist($schedule);
            }
            $manager->persist($company);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CompanyFixtures::class];
    }

}
