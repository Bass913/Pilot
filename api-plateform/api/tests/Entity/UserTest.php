<?php

namespace App\Tests\Entity;

use App\Entity\Company;
use App\Entity\Booking;
use App\Entity\Review;
use App\Entity\Schedule;
use App\Entity\Unavailability;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class UserTest extends TestCase
{
    public function testGetSetFirstname()
    {
        $user = new User();
        $user->setFirstname('John');
        $this->assertEquals('John', $user->getFirstname());
    }

    public function testGetSetLastname()
    {
        $user = new User();
        $user->setLastname('Doe');
        $this->assertEquals('Doe', $user->getLastname());
    }

    public function testGetSetEmail()
    {
        $user = new User();
        $user->setEmail('john.doe@example.com');
        $this->assertEquals('john.doe@example.com', $user->getEmail());
    }

    public function testGetSetPassword()
    {
        $user = new User();
        $user->setPassword('password123');
        $this->assertEquals('password123', $user->getPassword());
    }
    public function testGetSetRoles()
    {
        $user = new User();
        $roles = ['ROLE_EMPLOYEE'];
        $user->setRoles($roles);
        $this->assertEquals(['ROLE_EMPLOYEE', 'ROLE_USER'], $user->getRoles());
    }

    public function testGetSetPhone()
    {
        $user = new User();
        $user->setPhone('1234567890');
        $this->assertEquals('1234567890', $user->getPhone());
    }

    public function testAddRemoveUnavailability()
    {
        $user = new User();
        $unavailability = new Unavailability();
        $user->addUnavailability($unavailability);
        $this->assertTrue($user->getUnavailabilities()->contains($unavailability));

        $user->removeUnavailability($unavailability);
        $this->assertFalse($user->getUnavailabilities()->contains($unavailability));
    }
    public function testAddRemoveSchedule()
    {
        $user = new User();
        $schedule = new Schedule();
        $user->addSchedule($schedule);
        $this->assertTrue($user->getSchedules()->contains($schedule));

        $user->removeSchedule($schedule);
        $this->assertFalse($user->getSchedules()->contains($schedule));
    }


    public function testAddRemoveClientBooking()
    {
        $user = new User();
        $booking = new Booking();
        $user->addClientBooking($booking);
        $this->assertTrue($user->getClientBookings()->contains($booking));

        $user->removeClientBooking($booking);
        $this->assertFalse($user->getClientBookings()->contains($booking));
    }

    public function testAddRemoveEmployeeBooking()
    {
        $user = new User();
        $booking = new Booking();
        $user->addEmployeeBooking($booking);
        $this->assertTrue($user->getEmployeeBookings()->contains($booking));

        $user->removeEmployeeBooking($booking);
        $this->assertFalse($user->getEmployeeBookings()->contains($booking));
    }

    public function testAddRemoveCompany()
    {
        $user = new User();
        $company = new Company();
        $user->addCompany($company);
        $this->assertTrue($user->getCompanies()->contains($company));

        $user->removeCompany($company);
        $this->assertFalse($user->getCompanies()->contains($company));
    }

    public function testAddRemoveReview()
    {
        $user = new User();
        $review = new Review();
        $user->addReview($review);
        $this->assertTrue($user->getReviews()->contains($review));

        $user->removeReview($review);
        $this->assertFalse($user->getReviews()->contains($review));
    }
    public function testGetSetActive()
    {
        $user = new User();
        $user->setActive(true);
        $this->assertTrue($user->isActive());
    }

    public function testGetSetCompany()
    {
        $user = new User();
        $company = new Company();
        $user->setCompany($company);
        $this->assertEquals($company, $user->getCompany());
    }
    public function testGetId()
    {
        $user = new User();

        $reflection = new \ReflectionClass($user);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $uuid = Uuid::v4();
        $property->setValue($user, $uuid);

        $this->assertEquals($uuid, $user->getId());
    }
    public function testEraseCredentials()
    {
        $user = new User();
        $user->eraseCredentials();
        $this->assertNull($user->getPassword());
    }
    public function testGetUserIdentifier()
    {
        $user = new User();
        $user->setEmail('john.doe@example.com');
        $this->assertEquals('john.doe@example.com', $user->getUserIdentifier());
    }
}
