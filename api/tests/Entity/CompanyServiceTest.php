<?php

namespace App\Tests\Entity;

use App\Entity\Booking;
use App\Entity\Company;
use App\Entity\CompanyService;
use App\Entity\Service;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class CompanyServiceTest extends TestCase
{
    public function testInitialization(): void
    {
        $companyService = new CompanyService();

        $this->assertInstanceOf(CompanyService::class, $companyService);
        $this->assertNull($companyService->getId());
        $this->assertNull($companyService->getPrice());
        $this->assertNull($companyService->getDuration());
        $this->assertNull($companyService->getCompany());
        $this->assertNull($companyService->getService());
        $this->assertInstanceOf(ArrayCollection::class, $companyService->getBookings());
        $this->assertCount(0, $companyService->getBookings());
    }

    public function testGetSetPrice(): void
    {
        $companyService = new CompanyService();
        $companyService->setPrice('100.00');
        $this->assertEquals('100.00', $companyService->getPrice());
    }

    public function testGetSetDuration(): void
    {
        $companyService = new CompanyService();
        $companyService->setDuration(60);
        $this->assertEquals(60, $companyService->getDuration());
    }

    public function testGetSetCompany(): void
    {
        $companyService = new CompanyService();
        $company = new Company();
        $companyService->setCompany($company);
        $this->assertEquals($company, $companyService->getCompany());
    }

    public function testGetSetService(): void
    {
        $companyService = new CompanyService();
        $service = new Service();
        $companyService->setService($service);
        $this->assertEquals($service, $companyService->getService());
    }

    public function testAddRemoveBooking(): void
    {
        $companyService = new CompanyService();
        $booking1 = new Booking();
        $booking2 = new Booking();

        $companyService->addBooking($booking1);
        $this->assertTrue($companyService->getBookings()->contains($booking1));
        $this->assertEquals($companyService, $booking1->getCompanyService());

        $companyService->addBooking($booking2);
        $this->assertTrue($companyService->getBookings()->contains($booking2));
        $this->assertEquals($companyService, $booking2->getCompanyService());

        $companyService->removeBooking($booking1);
        $this->assertFalse($companyService->getBookings()->contains($booking1));
        $this->assertNull($booking1->getCompanyService());

        $companyService->removeBooking($booking2);
        $this->assertFalse($companyService->getBookings()->contains($booking2));
        $this->assertNull($booking2->getCompanyService());
    }
}
