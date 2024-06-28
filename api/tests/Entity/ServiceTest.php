<?php

namespace App\Tests\Entity;

use App\Entity\Service;
use App\Entity\Speciality;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class ServiceTest extends TestCase
{

    public function testSetName(): void
    {
        $service = new Service();
        $name = 'Haircut';

        $service->setName($name);

        $this->assertEquals($name, $service->getName());
    }

    public function testGetSetSpeciality(): void
    {
        $service = new Service();
        $speciality = new Speciality();

        $service->setSpeciality($speciality);

        $this->assertInstanceOf(Speciality::class, $service->getSpeciality());
        $this->assertEquals($speciality, $service->getSpeciality());
    }
}
