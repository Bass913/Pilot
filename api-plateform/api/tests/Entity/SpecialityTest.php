<?php

namespace App\Tests\Entity;

use App\Entity\Speciality;
use App\Entity\Company;
use App\Entity\Service;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Uid\Uuid;

class SpecialityTest extends TestCase
{
    public function testGetSetName(): void
    {
        $speciality = new Speciality();
        $this->assertNull($speciality->getName());

        $speciality->setName('Test Speciality');
        $this->assertEquals('Test Speciality', $speciality->getName());
    }


    public function testCompaniesCollection(): void
    {
        $speciality = new Speciality();
        $this->assertInstanceOf(ArrayCollection::class, $speciality->getCompanies());
        $this->assertCount(0, $speciality->getCompanies());

        $company1 = new Company();
        $company2 = new Company();

        $speciality->addCompany($company1);
        $this->assertCount(1, $speciality->getCompanies());
        $this->assertTrue($speciality->getCompanies()->contains($company1));
        $this->assertFalse($speciality->getCompanies()->contains($company2));

        $speciality->addCompany($company2);
        $this->assertCount(2, $speciality->getCompanies());
        $this->assertTrue($speciality->getCompanies()->contains($company2));

        $speciality->removeCompany($company1);
        $this->assertCount(1, $speciality->getCompanies());
        $this->assertFalse($speciality->getCompanies()->contains($company1));
    }

    public function testServicesCollection(): void
    {
        $speciality = new Speciality();
        $this->assertInstanceOf(ArrayCollection::class, $speciality->getServices());
        $this->assertCount(0, $speciality->getServices());

        $service1 = new Service();
        $service2 = new Service();

        $speciality->addService($service1);
        $this->assertCount(1, $speciality->getServices());
        $this->assertTrue($speciality->getServices()->contains($service1));
        $this->assertFalse($speciality->getServices()->contains($service2));

        $speciality->addService($service2);
        $this->assertCount(2, $speciality->getServices());
        $this->assertTrue($speciality->getServices()->contains($service2));

        $speciality->removeService($service1);
        $this->assertCount(1, $speciality->getServices());
        $this->assertFalse($speciality->getServices()->contains($service1));
    }
}
