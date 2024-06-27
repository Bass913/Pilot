<?php

namespace App\Tests\Entity;

use App\Entity\Unavailability;
use App\Entity\User;
use App\Entity\Company;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class UnavailabilityTest extends TestCase
{
    public function testGetSetStartDate()
    {
        $unavailability = new Unavailability();
        $startDate = new \DateTime('2024-06-27');
        $unavailability->setStartDate($startDate);
        $this->assertEquals($startDate, $unavailability->getStartDate());
    }

    public function testGetSetEndDate()
    {
        $unavailability = new Unavailability();
        $endDate = new \DateTime('2024-06-28');
        $unavailability->setEndDate($endDate);
        $this->assertEquals($endDate, $unavailability->getEndDate());
    }

    public function testGetSetUser()
    {
        $unavailability = new Unavailability();
        $user = new User();
        $unavailability->setUser($user);
        $this->assertEquals($user, $unavailability->getUser());
    }

    public function testGetSetCompany()
    {
        $unavailability = new Unavailability();
        $company = new Company();
        $unavailability->setCompany($company);
        $this->assertEquals($company, $unavailability->getCompany());
    }

    public function testGetId()
    {
        $unavailability = new Unavailability();

        $reflection = new \ReflectionClass($unavailability);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $uuid = Uuid::v4();
        $property->setValue($unavailability, $uuid);

        $this->assertEquals($uuid, $unavailability->getId());
    }
}
