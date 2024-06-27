<?php

namespace App\Tests\Entity;

use App\Entity\CategoryReview;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class CategoryReviewTest extends TestCase
{
    public function testInitialization(): void
    {
        $categoryReview = new CategoryReview();

        $this->assertInstanceOf(CategoryReview::class, $categoryReview);
        $this->assertNull($categoryReview->getId());
        $this->assertNull($categoryReview->getName());
    }

    public function testGetSetMethods(): void
    {
        $categoryReview = new CategoryReview();

        $categoryReview->setName('Category Name');
        $this->assertEquals('Category Name', $categoryReview->getName());
    }

    public function testAnnotations(): void
    {
        $reflectionClass = new \ReflectionClass(CategoryReview::class);
        $classAnnotations = $reflectionClass->getAttributes();

        $this->assertCount(2, $classAnnotations);

        $idProperty = $reflectionClass->getProperty('id');
        $idAnnotations = $idProperty->getAttributes();
        $this->assertCount(4, $idAnnotations);

        $nameProperty = $reflectionClass->getProperty('name');
        $nameAnnotations = $nameProperty->getAttributes();
        $this->assertCount(2, $nameAnnotations);
    }
}
