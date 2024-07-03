<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ReviewSubscriber implements EventSubscriberInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['incrementReviewCount', EventPriorities::POST_WRITE],
        ];
    }

    public function incrementReviewCount(ViewEvent $event): void
    {
        $review = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$review instanceof Review || Request::METHOD_POST !== $method) {
            return;
        }

        $company = $review->getCompany();
            $currentReviewCount = $company->getReviewCount() ?? 0;
            $company->setReviewCount($currentReviewCount + 1);

            $this->entityManager->persist($company);
            $this->entityManager->flush();
    }
}
