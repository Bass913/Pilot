<?php

namespace App\Tests\Entity;

use App\Entity\Company;
use App\Entity\CompanyService;
use App\Entity\Speciality;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class CompanyTest extends TestCase
{
    public function testInitialization(): void
    {
        $company = new Company();
        $speciality = new Speciality();
        $company->setSpeciality($speciality);

        $this->assertInstanceOf(Speciality::class, $company->getSpeciality());
        $this->assertEquals($speciality, $company->getSpeciality());
        $this->assertInstanceOf(Company::class, $company);
        $this->assertNull($company->getId());
        $this->assertNull($company->getName());
        $this->assertNull($company->getAddress());
        $this->assertNull($company->getDescription());
        $this->assertNull($company->getZipcode());
        $this->assertNull($company->getCity());
        $this->assertNull($company->isActive());
        $this->assertNull($company->getLongitude());
        $this->assertNull($company->getLatitude());
        $this->assertInstanceOf(ArrayCollection::class, $company->getCompanyServices());
        $this->assertCount(0, $company->getCompanyServices());
        $this->assertInstanceOf(ArrayCollection::class, $company->getReviews());
        $this->assertCount(0, $company->getReviews());
        $this->assertNull($company->getReviewRating());
        $this->assertInstanceOf(ArrayCollection::class, $company->getUnavailabilities());
        $this->assertCount(0, $company->getUnavailabilities());
        $this->assertInstanceOf(ArrayCollection::class, $company->getSchedules());
        $this->assertCount(0, $company->getSchedules());
        $this->assertInstanceOf(ArrayCollection::class, $company->getUsers());
        $this->assertCount(0, $company->getUsers());
        $this->assertInstanceOf(Speciality::class, $company->getSpeciality());
        $this->assertNull($company->getReviewCount());
        $this->assertNull($company->getImages());
        $this->assertNull($company->getUser());
        $this->assertInstanceOf(ArrayCollection::class, $company->getBookings());
        $this->assertCount(0, $company->getBookings());
    }

    public function testGetSetMethods(): void
    {
        $company = new Company();

        $company->setName('Company Name');
        $this->assertEquals('Company Name', $company->getName());
    }

    public function testAddRemoveMethods(): void
    {
        $company = new Company();

        $companyService = new CompanyService();
        $company->addCompanyService($companyService);
        $this->assertTrue($company->getCompanyServices()->contains($companyService));
        $this->assertEquals($company, $companyService->getCompany());
    }
}
