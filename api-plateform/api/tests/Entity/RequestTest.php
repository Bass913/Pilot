<?php

namespace App\Tests\Entity;

use App\Entity\Request;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

class RequestTest extends TestCase
{
    public function testInitialization(): void
    {
        $request = new Request();

        $this->assertInstanceOf(Request::class, $request);
        $this->assertNull($request->getId());
        $this->assertNull($request->getFirstname());
        $this->assertNull($request->getLastname());
        $this->assertNull($request->getPhone());
        $this->assertNull($request->getEmail());
        $this->assertNull($request->getFile());
        $this->assertNull($request->getKbis());
        $this->assertNull($request->getCreatedAt());
        $this->assertNull($request->getUpdatedAt());
    }

    public function testGetSetFirstname(): void
    {
        $request = new Request();
        $request->setFirstname('John');
        $this->assertEquals('John', $request->getFirstname());
    }

    public function testGetSetLastname(): void
    {
        $request = new Request();
        $request->setLastname('Doe');
        $this->assertEquals('Doe', $request->getLastname());
    }

    public function testGetSetPhone(): void
    {
        $request = new Request();
        $request->setPhone('1234567890');
        $this->assertEquals('1234567890', $request->getPhone());
    }

    public function testGetSetEmail(): void
    {
        $request = new Request();
        $request->setEmail('john.doe@example.com');
        $this->assertEquals('john.doe@example.com', $request->getEmail());
    }

    public function testSetAndGetFile(): void
    {
        $request = new Request();
        $file = $this->createMock(UploadedFile::class);
        $file->method('getMimeType')->willReturn('application/pdf');

        $request->setFile($file);
        $this->assertInstanceOf(UploadedFile::class, $request->getFile());
    }

    public function testSetAndGetKbis(): void
    {
        $request = new Request();
        $request->setKbis('kbis_filename.pdf');
        $this->assertEquals('kbis_filename.pdf', $request->getKbis());
    }

    public function testSetAndGetCreatedAt(): void
    {
        $request = new Request();
        $createdAt = new \DateTimeImmutable();
        $request->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $request->getCreatedAt());
    }

    public function testSetAndGetUpdatedAt(): void
    {
        $request = new Request();
        $updatedAt = new \DateTimeImmutable();
        $request->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $request->getUpdatedAt());
    }
}
