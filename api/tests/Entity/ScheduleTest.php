<?php

namespace App\Tests\Entity;

use App\Entity\Schedule;
use App\Entity\Company;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ScheduleTest extends TestCase
{
    public function testSetDayOfWeek(): void
    {
        $schedule = new Schedule();
        $dayOfWeek = 'Monday';

        $schedule->setDayOfWeek($dayOfWeek);

        $this->assertEquals($dayOfWeek, $schedule->getDayOfWeek());
    }

    public function testSetStartTime(): void
    {
        $schedule = new Schedule();
        $startTime = new \DateTime('08:00:00');

        $schedule->setStartTime($startTime);

        $this->assertEquals($startTime, $schedule->getStartTime());
    }

    public function testSetEndTime(): void
    {
        $schedule = new Schedule();
        $endTime = new \DateTime('17:00:00');

        $schedule->setEndTime($endTime);

        $this->assertEquals($endTime, $schedule->getEndTime());
    }

    public function testSetCompany(): void
    {
        $schedule = new Schedule();
        $company = new Company();

        $schedule->setCompany($company);

        $this->assertEquals($company, $schedule->getCompany());
    }

    public function testSetUser(): void
    {
        $schedule = new Schedule();
        $user = new User();

        $schedule->setUser($user);

        $this->assertEquals($user, $schedule->getUser());
    }
}
