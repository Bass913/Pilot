<?php

namespace App\Tests\Entity;

use App\Entity\Booking;
use App\Entity\Company;
use App\Entity\CompanyService;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class BookingTest extends TestCase
{
    public function testInitialization(): void
    {
        $booking = new Booking();

        $this->assertInstanceOf(Booking::class, $booking);
        $this->assertNull($booking->getId());
        $this->assertNull($booking->getStartDate());
        $this->assertNull($booking->getStatus());
        $this->assertNull($booking->getCompanyService());
        $this->assertNull($booking->getClient());
        $this->assertNull($booking->getEmployee());
        $this->assertNull($booking->getCompany());
    }

    public function testGetSetMethods(): void
    {
        $booking = new Booking();

        $startDate = '2024-06-28T09:00:00';
        $booking->setStartDate($startDate);
        $this->assertEquals($startDate, $booking->getStartDate());

        $status = 'confirmed';
        $booking->setStatus($status);
        $this->assertEquals($status, $booking->getStatus());

        $companyService = new CompanyService();
        $booking->setCompanyService($companyService);
        $this->assertEquals($companyService, $booking->getCompanyService());

        $client = new User();
        $booking->setClient($client);
        $this->assertEquals($client, $booking->getClient());

        $employee = new User();
        $booking->setEmployee($employee);
        $this->assertEquals($employee, $booking->getEmployee());

        $company = new Company();
        $booking->setCompany($company);
        $this->assertEquals($company, $booking->getCompany());
    }



    public function testApiPlatformAnnotations(): void
    {
        $reflectionClass = new \ReflectionClass(Booking::class);
        $classAnnotations = $reflectionClass->getAttributes();

        $this->assertCount(2, $classAnnotations); // Check for attributes on the class (ORM\Entity and ApiResource)

        $idProperty = $reflectionClass->getProperty('id');
        $idAnnotations = $idProperty->getAttributes();
        $this->assertCount(4, $idAnnotations);

        $startDateProperty = $reflectionClass->getProperty('startDate');
        $startDateAnnotations = $startDateProperty->getAttributes();
        $this->assertCount(2, $startDateAnnotations);

        $statusProperty = $reflectionClass->getProperty('status');
        $statusAnnotations = $statusProperty->getAttributes();
        $this->assertCount(2, $statusAnnotations);

        $companyServiceProperty = $reflectionClass->getProperty('companyService');
        $companyServiceAnnotations = $companyServiceProperty->getAttributes();
        $this->assertCount(2, $companyServiceAnnotations);


        $clientProperty = $reflectionClass->getProperty('client');
        $clientAnnotations = $clientProperty->getAttributes();
        $this->assertCount(1, $clientAnnotations);

        $employeeProperty = $reflectionClass->getProperty('employee');
        $employeeAnnotations = $employeeProperty->getAttributes();
        $this->assertCount(2, $employeeAnnotations);

        $companyProperty = $reflectionClass->getProperty('company');
        $companyAnnotations = $companyProperty->getAttributes();
        $this->assertCount(2, $companyAnnotations);
    }
}
