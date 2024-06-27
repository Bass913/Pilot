<?php

namespace App\Tests\Entity;

use App\Entity\Review;
use App\Entity\Company;
use App\Entity\Rating;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ReviewTest extends TestCase
{

    public function testSetAndGetDate(): void
    {
        $review = new Review();
        $date = '2024-06-28';

        $review->setDate($date);

        $this->assertEquals($date, $review->getDate());
    }

    public function testSetAndGetCompany(): void
    {
        $review = new Review();
        $company = new Company();

        $review->setCompany($company);

        $this->assertEquals($company, $review->getCompany());
    }

    public function testSetAndGetComment(): void
    {
        $review = new Review();
        $comment = 'This is a test comment.';

        $review->setComment($comment);

        $this->assertEquals($comment, $review->getComment());
    }

    public function testAddRemoveRating(): void
    {
        $review = new Review();
        $rating = new Rating();

        $review->addRating($rating);
        $this->assertTrue($review->getRatings()->contains($rating));

        $review->removeRating($rating);
        $this->assertFalse($review->getRatings()->contains($rating));
    }

    public function testSetAndGetClient(): void
    {
        $review = new Review();
        $client = new User();

        $review->setClient($client);

        $this->assertEquals($client, $review->getClient());
    }

    public function testConstruct(): void
    {
        $review = new Review();

        $date = $review->getDate();
        if ($date !== null) {
            $dateTime = \DateTime::createFromFormat('Y-m-d', $date);
            $this->assertInstanceOf(\DateTimeInterface::class, $dateTime);
        }

        $company = $review->getCompany();
        if ($company !== null) {
            $this->assertInstanceOf(Company::class, $company);
        }

        $client = $review->getClient();
        if ($client !== null) {
            $this->assertInstanceOf(User::class, $client);
        }

        $this->assertInstanceOf(\Doctrine\Common\Collections\Collection::class, $review->getRatings());
    }
}
