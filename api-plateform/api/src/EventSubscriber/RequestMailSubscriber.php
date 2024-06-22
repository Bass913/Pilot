<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Request as RequestEntity;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\MailerInterface;

final class RequestMailSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['sendMail', EventPriorities::POST_WRITE],
        ];
    }

    public function sendMail(ViewEvent $event): void
    {
        $request = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$request instanceof RequestEntity || Request::METHOD_POST !== $method) {
            return;
        }
        date_default_timezone_set('Europe/Paris');
        $currentHour = date('H:i');
        $greetings = "Bonjour";
        if ($currentHour >= '17:30') {
            $greetings = "Bonsoir";
        }
            $message = (new TemplatedEmail())
            ->from('challengepilot@gmail.com')
            ->to($request->getEmail())
            ->cc("wtr.esgi@gmail.com")
            ->subject("Demande d'ajout d'établissement")
            ->htmlTemplate('emails/request.html.twig')
            ->context([
                'firstname' => $request->getFirstname(),
                'greetings' => $greetings,
            ]);      
        $this->mailer->send($message);
    }
}