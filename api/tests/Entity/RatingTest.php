<?php

namespace App\Tests\Entity;

use App\Entity\CategoryReview;
use App\Entity\Rating;
use App\Entity\Review;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class RatingTest extends TestCase
{
    public function testInitialization(): void
    {
        $rating = new Rating();

        $this->assertInstanceOf(Rating::class, $rating);
        $this->assertNull($rating->getId());
        $this->assertNull($rating->getReview());
        $this->assertNull($rating->getCategory());
        $this->assertNull($rating->getValue());
    }

    public function testGetSetReview(): void
    {
        $rating = new Rating();
        $review = new Review();
        $rating->setReview($review);
        $this->assertEquals($review, $rating->getReview());
    }

    public function testGetSetCategory(): void
    {
        $rating = new Rating();
        $category = new CategoryReview();
        $rating->setCategory($category);
        $this->assertEquals($category, $rating->getCategory());
    }

    public function testGetSetValue(): void
    {
        $rating = new Rating();
        $rating->setValue(4);
        $this->assertEquals(4, $rating->getValue());
    }
}
