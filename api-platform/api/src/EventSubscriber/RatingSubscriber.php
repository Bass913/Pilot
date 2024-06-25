<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Rating;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class RatingSubscriber implements EventSubscriberInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['updateReviewRating', EventPriorities::POST_WRITE],
        ];
    }

    public function updateReviewRating(ViewEvent $event): void
    {
        $rating = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$rating instanceof Rating || Request::METHOD_POST !== $method) {
            return;
        }

        $review = $rating->getReview();
        $ratings = $review->getRatings();

        if ($ratings !== null && $ratings->count() > 0) {
            $totalRatings = $ratings->count();
            $sumRatings = 0;

            foreach ($ratings as $ratingItem) {
                $sumRatings += $ratingItem->getValue();
            }

            $newAverageRating = $sumRatings / $totalRatings;
        } else {
            $newAverageRating = $rating->getValue();
        }

        $company = $review->getCompany();
            $company->setReviewRating($newAverageRating);
            $this->entityManager->persist($company);
            $this->entityManager->flush();
    }
}
